@extends('layouts.app')

@section('title', 'Add Product - WMS')

@section('content')
<div class="container mt-4 mb-4">

  <div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-2"></i>Back to Inventory
    </a>
  </div>

  <h3 class="mb-4 fw-bold">
    <i class="fa-solid fa-plus-circle me-2"></i>Add New Product
  </h3>

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Oops!</strong> Please fix the following errors:
    <ul class="mb-0 mt-2">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="bg-white rounded shadow-sm p-4">
    <form action="{{ route('products.store') }}" method="POST">
      @csrf

      <div class="row g-3">

        <!-- SKU -->
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-barcode me-1"></i>SKU
            <span class="text-danger">*</span>
          </label>
          <input
            type="text"
            name="sku"
            class="form-control @error('sku') is-invalid @enderror"
            placeholder="e.g., P001, WID-001"
            value="{{ old('sku') }}"
            required
            autofocus>
          @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Stock Keeping Unit - must be unique</small>
        </div>

        <!-- Product Name -->
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-tag me-1"></i>Product Name
            <span class="text-danger">*</span>
          </label>
          <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="e.g., Laptop Asus ROG"
            value="{{ old('name') }}"
            required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Stock Quantity -->
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-boxes-stacked me-1"></i>Stock Quantity
            <span class="text-danger">*</span>
          </label>
          <input
            type="number"
            name="stock"
            class="form-control @error('stock') is-invalid @enderror"
            placeholder="e.g., 150"
            value="{{ old('stock', 0) }}"
            min="0"
            required>
          @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Location -->
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-location-dot me-1"></i>Rack Location
          </label>
          <select name="location" class="form-select @error('location') is-invalid @enderror">
            <option value="">-- Select Location --</option>
            <option value="A-01" {{ old('location') == 'A-01' ? 'selected' : '' }}>A-01</option>
            <option value="A-02" {{ old('location') == 'A-02' ? 'selected' : '' }}>A-02</option>
            <option value="A-03" {{ old('location') == 'A-03' ? 'selected' : '' }}>A-03</option>
            <option value="B-05" {{ old('location') == 'B-05' ? 'selected' : '' }}>B-05</option>
            <option value="B-06" {{ old('location') == 'B-06' ? 'selected' : '' }}>B-06</option>
            <option value="C-01" {{ old('location') == 'C-01' ? 'selected' : '' }}>C-01</option>
            <option value="C-02" {{ old('location') == 'C-02' ? 'selected' : '' }}>C-02</option>
          </select>
          @error('location')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Optional - warehouse rack position</small>
        </div>

        <!-- Description -->
        <div class="col-12">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-align-left me-1"></i>Description
            <span class="text-muted">(Optional)</span>
          </label>
          <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="3"
            placeholder="Enter product description, specifications, or notes...">{{ old('description') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Submit Button -->
        <div class="col-12 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-save me-2"></i>Save Product
          </button>
          <a href="{{ route('products.index') }}" class="btn btn-secondary px-4 ms-2">
            <i class="fa-solid fa-times me-2"></i>Cancel
          </a>
        </div>

      </div>
    </form>
  </div>

</div>
@endsection
