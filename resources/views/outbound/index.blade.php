@extends('layouts.app')

@section('title', 'Outbound - WMS')

@section('content')
<div class="container mt-4">

  <h3 class="mb-4 fw-bold">
    <i class="fa-solid fa-arrow-up-wide-short me-2"></i>
    Outbound Transaction
  </h3>

  <!-- SUCCESS/ERROR MESSAGES -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong>
    <ul class="mb-0 mt-2">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- OUTBOUND FORM -->
  <form action="{{ route('outbound.store') }}" method="POST" class="row g-3 shadow-sm p-4 bg-white rounded mb-5">
    @csrf

    <div class="col-md-6">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-barcode me-1"></i>SKU <span class="text-danger">*</span>
      </label>
      <input
        type="text"
        name="product_code"
        class="form-control"
        required
        placeholder="e.g., P001"
        value="{{ old('product_code') }}">
      <small class="text-muted">Enter existing product SKU</small>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-boxes-stacked me-1"></i>Quantity <span class="text-danger">*</span>
      </label>
      <input
        type="number"
        name="quantity"
        class="form-control"
        required
        min="1"
        placeholder="e.g., 20"
        value="{{ old('quantity') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-location-arrow me-1"></i>Destination <span class="text-danger">*</span>
      </label>
      <input
        type="text"
        name="destination"
        class="form-control"
        required
        placeholder="e.g., Customer ABC"
        value="{{ old('destination') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-truck me-1"></i>Dispatch Type <span class="text-danger">*</span>
      </label>
      <select name="dispatch_type" class="form-select" required>
        <option value="">-- Select --</option>
        <option value="Delivery">Delivery</option>
        <option value="Pickup">Pickup</option>
        <option value="Transfer">Transfer</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-note-sticky me-1"></i>Notes
      </label>
      <input
        type="text"
        name="notes"
        class="form-control"
        placeholder="Optional notes">
    </div>

    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-danger px-4">
        <i class="fa-solid fa-paper-plane me-2"></i>Submit Outbound
      </button>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4 ms-2">
        <i class="fa-solid fa-arrow-left me-2"></i>Dashboard
      </a>
    </div>

  </form>

  <!-- TRANSACTION HISTORY -->
  <h4 class="mb-3 fw-bold">
    <i class="fa-solid fa-clock-rotate-left me-2"></i>
    Transaction History
  </h4>

  <div class="table-responsive bg-white rounded shadow-sm p-3 mb-4">
    <table class="table table-striped table-hover mb-0">
      <thead class="table-dark">
        <tr>
          <th class="text-center">#</th>
          <th class="text-center"><i class="fa-solid fa-barcode me-2"></i>SKU</th>
          <th class="text-center"><i class="fa-solid fa-box me-2"></i>Product</th>
          <th class="text-center"><i class="fa-solid fa-hashtag me-2"></i>Qty</th>
          <th class="text-center"><i class="fa-solid fa-location-arrow me-2"></i>Destination</th>
          <th class="text-center"><i class="fa-solid fa-truck me-2"></i>Type</th>
          <th class="text-center"><i class="fa-solid fa-calendar me-2"></i>Date</th>
          <th class="text-center"><i class="fa-solid fa-gear me-2"></i>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $transaction)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td class="text-center">{{ $transaction->sku }}</td>
          <td class="text-center">{{ $transaction->product_name }}</td>
          <td class="text-center">
            <span class="badge bg-danger">-{{ $transaction->quantity }}</span>
          </td>
          <td class="text-center">{{ $transaction->destination }}</td>
          <td class="text-center">
            <span class="badge bg-secondary">{{ $transaction->dispatch_type }}</span>
          </td>
          <td class="text-center">
            <small>{{ $transaction->created_at->format('d M Y, H:i') }}</small>
          </td>
          <td class="text-center">
            <div class="btn-group" role="group">
              <a href="{{ route('outbound.edit', $transaction->id) }}"
                 class="btn btn-sm btn-warning"
                 title="Edit">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <button type="button"
                      class="btn btn-sm btn-danger"
                      onclick="deleteTransaction({{ $transaction->id }})"
                      title="Delete">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
            <form id="delete-outbound-{{ $transaction->id }}"
                  action="{{ route('outbound.destroy', $transaction->id) }}"
                  method="POST"
                  style="display: none;">
              @csrf
              @method('DELETE')
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-4">
            <i class="fa-solid fa-box-open fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
            No transactions yet. Create your first outbound transaction above.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- AVAILABLE STOCK -->
  <h4 class="mb-3 fw-bold">
    <i class="fa-solid fa-warehouse me-2"></i>
    Available Stock
  </h4>

  <div class="table-responsive bg-white rounded shadow-sm p-3">
    <table class="table table-hover mb-0">
      <thead class="table-dark">
        <tr>
          <th class="text-center"><i class="fa-solid fa-barcode me-2"></i>SKU</th>
          <th class="text-center"><i class="fa-solid fa-box-open me-2"></i>Product</th>
          <th class="text-center"><i class="fa-solid fa-chart-column me-2"></i>Stock</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          <td class="text-center">{{ $product->sku }}</td>
          <td class="text-center">{{ $product->name }}</td>
          <td class="text-center">
            <span class="badge {{ $product->stock < 10 ? 'bg-danger' : ($product->stock < 50 ? 'bg-warning text-dark' : 'bg-success') }}">
              {{ $product->stock }}
            </span>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="text-center text-muted py-3">No products available</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@push('scripts')
<script>
function deleteTransaction(id) {
  if (confirm('Delete this transaction? Stock will be restored automatically.')) {
    document.getElementById('delete-outbound-' + id).submit();
  }
}
</script>
@endpush
@endsection
