@extends('layouts.app')

@section('title', 'Edit Inbound Transaction')

@section('content')
<div class="container mt-4 mb-4">

  <div class="mb-4">
    <a href="{{ route('inbound.index') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-2"></i>Back to Inbound
    </a>
  </div>

  <h3 class="mb-4 fw-bold">
    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Inbound Transaction
  </h3>

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

  <div class="bg-white rounded shadow-sm p-4">
    <form action="{{ route('inbound.update', $transaction->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">

        <div class="col-md-6">
          <label class="form-label fw-bold">SKU</label>
          <input type="text" class="form-control" value="{{ $transaction->sku }}" disabled>
          <small class="text-muted">Cannot be changed</small>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">Product Name</label>
          <input type="text" class="form-control" value="{{ $transaction->product_name }}" disabled>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
          <input
            type="number"
            name="quantity"
            class="form-control"
            value="{{ old('quantity', $transaction->quantity) }}"
            min="1"
            required>
          <small class="text-muted">Original: {{ $transaction->quantity }}</small>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
          <select name="location" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="A-01" {{ old('location', $transaction->location) == 'A-01' ? 'selected' : '' }}>A-01</option>
            <option value="A-02" {{ old('location', $transaction->location) == 'A-02' ? 'selected' : '' }}>A-02</option>
            <option value="A-03" {{ old('location', $transaction->location) == 'A-03' ? 'selected' : '' }}>A-03</option>
            <option value="B-05" {{ old('location', $transaction->location) == 'B-05' ? 'selected' : '' }}>B-05</option>
            <option value="B-06" {{ old('location', $transaction->location) == 'B-06' ? 'selected' : '' }}>B-06</option>
            <option value="C-01" {{ old('location', $transaction->location) == 'C-01' ? 'selected' : '' }}>C-01</option>
            <option value="C-02" {{ old('location', $transaction->location) == 'C-02' ? 'selected' : '' }}>C-02</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">Notes</label>
          <input
            type="text"
            name="notes"
            class="form-control"
            value="{{ old('notes', $transaction->notes) }}"
            placeholder="Optional notes">
        </div>

        <div class="col-12">
          <div class="alert alert-info">
            <small>
              <i class="fa-solid fa-info-circle me-1"></i>
              <strong>Created:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}
            </small>
          </div>
        </div>

        <div class="col-12 mt-4">
          <button type="submit" class="btn btn-warning px-4">
            <i class="fa-solid fa-save me-2"></i>Update Transaction
          </button>
          <a href="{{ route('inbound.index') }}" class="btn btn-secondary px-4 ms-2">
            <i class="fa-solid fa-times me-2"></i>Cancel
          </a>
        </div>

      </div>
    </form>
  </div>

</div>
@endsection
