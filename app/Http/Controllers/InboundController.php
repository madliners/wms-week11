<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InboundTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{
    // List all transactions
    public function index()
    {
        $transactions = InboundTransaction::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        $products = Product::where('user_id', Auth::id())->latest()->get();

        return view('inbound.index', compact('transactions', 'products'));
    }

    // Store new inbound transaction
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required',
            'product_name' => 'required',
            'quantity' => 'required|integer|min:1',
            'location' => 'required',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Cari atau buat produk
            $product = Product::where('sku', $request->product_code)
                ->where('user_id', Auth::id())
                ->first();

            if ($product) {
                // Update stock existing product
                $product->stock += $request->quantity;
                $product->save();
            } else {
                // Buat produk baru
                $product = Product::create([
                    'user_id' => Auth::id(),
                    'sku' => $request->product_code,
                    'name' => $request->product_name,
                    'stock' => $request->quantity,
                    'location' => $request->location
                ]);
            }

            // Record transaction
            InboundTransaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'sku' => $request->product_code,
                'product_name' => $request->product_name,
                'quantity' => $request->quantity,
                'location' => $request->location,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('inbound.index')
                ->with('success', 'Inbound transaction recorded successfully! Stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    // Edit form
    public function edit($id)
    {
        $transaction = InboundTransaction::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('inbound.edit', compact('transaction'));
    }

    // Update transaction
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'location' => 'required',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $transaction = InboundTransaction::where('user_id', Auth::id())
                ->findOrFail($id);

            $product = $transaction->product;

            // Adjust stock: hapus qty lama, tambah qty baru
            $stockDifference = $request->quantity - $transaction->quantity;
            $product->stock += $stockDifference;
            $product->location = $request->location;
            $product->save();

            // Update transaction record
            $transaction->update([
                'quantity' => $request->quantity,
                'location' => $request->location,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('inbound.index')
                ->with('success', 'Transaction updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    // Delete transaction
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $transaction = InboundTransaction::where('user_id', Auth::id())
                ->findOrFail($id);

            $product = $transaction->product;

            // Reverse the stock addition
            $product->stock -= $transaction->quantity;

            if ($product->stock < 0) {
                $product->stock = 0;
            }

            $product->save();

            // Delete transaction
            $transaction->delete();

            DB::commit();

            return redirect()->route('inbound.index')
                ->with('success', 'Transaction deleted and stock adjusted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
