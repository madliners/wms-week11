<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OutboundTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    // List all transactions
    public function index()
    {
        $transactions = OutboundTransaction::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        $products = Product::where('user_id', Auth::id())->latest()->get();

        return view('outbound.index', compact('transactions', 'products'));
    }

    // Store new outbound transaction
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required',
            'dispatch_type' => 'required',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Cari produk
            $product = Product::where('sku', $request->product_code)
                ->where('user_id', Auth::id())
                ->first();

            if (!$product) {
                return back()->withErrors(['product_code' => 'Product not found!']);
            }

            if ($product->stock < $request->quantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock! Available: ' . $product->stock]);
            }

            // Kurangi stock
            $product->stock -= $request->quantity;
            $product->save();

            // Record transaction
            OutboundTransaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'sku' => $product->sku,
                'product_name' => $product->name,
                'quantity' => $request->quantity,
                'destination' => $request->destination,
                'dispatch_type' => $request->dispatch_type,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('outbound.index')
                ->with('success', 'Outbound transaction recorded successfully! Stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    // Edit form
    public function edit($id)
    {
        $transaction = OutboundTransaction::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('outbound.edit', compact('transaction'));
    }

    // Update transaction
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'destination' => 'required',
            'dispatch_type' => 'required',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $transaction = OutboundTransaction::where('user_id', Auth::id())
                ->findOrFail($id);

            $product = $transaction->product;

            // Adjust stock: kembalikan qty lama, kurangi qty baru
            $stockDifference = $transaction->quantity - $request->quantity;
            $newStock = $product->stock + $stockDifference;

            if ($newStock < 0) {
                return back()->withErrors(['quantity' => 'Insufficient stock for this adjustment!']);
            }

            $product->stock = $newStock;
            $product->save();

            // Update transaction
            $transaction->update([
                'quantity' => $request->quantity,
                'destination' => $request->destination,
                'dispatch_type' => $request->dispatch_type,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('outbound.index')
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
            $transaction = OutboundTransaction::where('user_id', Auth::id())
                ->findOrFail($id);

            $product = $transaction->product;

            // Kembalikan stock
            $product->stock += $transaction->quantity;
            $product->save();

            // Delete transaction
            $transaction->delete();

            DB::commit();

            return redirect()->route('outbound.index')
                ->with('success', 'Transaction deleted and stock restored!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
