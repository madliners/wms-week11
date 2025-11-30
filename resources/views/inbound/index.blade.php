@extends('layouts.app')

@section('title', 'Inbound - WMS')

@section('content')
<div class="container mt-4">

  <h3 class="mb-4 fw-bold">
    <i class="fa-solid fa-arrow-down-wide-short me-2"></i>
    Inbound Transaction
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

  <!-- INBOUND FORM -->
  <form action="{{ route('inbound.store') }}" method="POST" class="row g-3 shadow-sm p-4 bg-white rounded mb-5">
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
      <small class="text-muted">Enter existing SKU or create new</small>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-tag me-1"></i>Product Name <span class="text-danger">*</span>
      </label>
      <input
        type="text"
        name="product_name"
        class="form-control"
        required
        placeholder="e.g., Laptop Asus"
        value="{{ old('product_name') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-boxes-stacked me-1"></i>Quantity <span class="text-danger">*</span>
      </label>
      <input
        type="number"
        name="quantity"
        class="form-control"
        required
        min="1"
        placeholder="e.g., 50"
        value="{{ old('quantity') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label fw-bold">
        <i class="fa-solid fa-location-dot me-1"></i>Location <span class="text-danger">*</span>
      </label>
      <select name="location" class="form-select" required>
        <option value="">-- Select --</option>
        <option value="A-01">A-01</option>
        <option value="A-02">A-02</option>
        <option value="A-03">A-03</option>
        <option value="B-05">B-05</option>
        <option value="B-06">B-06</option>
        <option value="C-01">C-01</option>
        <option value="C-02">C-02</option>
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
      <button type="submit" class="btn btn-primary px-4">
        <i class="fa-solid fa-paper-plane me-2"></i>Submit Inbound
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

  <div class="table-responsive bg-white rounded shadow-sm p-3">
    <table class="table table-striped table-hover mb-0">
      <thead class="table-dark">
        <tr>
          <th class="text-center">#</th>
          <th class="text-center"><i class="fa-solid fa-barcode me-2"></i>SKU</th>
          <th class="text-center"><i class="fa-solid fa-box me-2"></i>Product</th>
          <th class="text-center"><i class="fa-solid fa-hashtag me-2"></i>Qty</th>
          <th class="text-center"><i class="fa-solid fa-location-dot me-2"></i>Location</th>
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
            <span class="badge bg-success">+{{ $transaction->quantity }}</span>
          </td>
          <td class="text-center">{{ $transaction->location }}</td>
          <td class="text-center">
            <small>{{ $transaction->created_at->format('d M Y, H:i') }}</small>
          </td>
          <td class="text-center">
            <a href="{{ route('inbound.edit', $transaction->id) }}"
               class="btn btn-sm btn-warning"
               title="Edit">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('inbound.destroy', $transaction->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this transaction? Stock will be adjusted.');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
            No transactions yet. Create your first inbound transaction above.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection 
