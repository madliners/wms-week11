@extends('layouts.app')

@section('title', 'Edit Product - WMS')

@section('content')
<div class="container mt-4 mb-4">

  {{-- Back Button --}}
  <div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-2"></i>Back to Inventory
    </a>
  </div>

  <h3 class="mb-4 fw-bold">
    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Product: {{ $product->name }}
  </h3>

  {{-- Validation Errors --}}
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

    {{-- FORM UPDATE PRODUCT --}}
    <form action="{{ route('products.update', $product) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">

        {{-- SKU --}}
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-barcode me-1"></i>SKU
            <span class="text-danger">*</span>
          </label>
          <input
            type="text"
            name="sku"
            class="form-control @error('sku') is-invalid @enderror"
            placeholder="e.g., P001"
            value="{{ old('sku', $product->sku) }}"
            required>
          @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Stock Keeping Unit - must be unique</small>
        </div>

        {{-- Product Name --}}
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-tag me-1"></i>Product Name
            <span class="text-danger">*</span>
          </label>
          <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="e.g., Laptop Asus"
            value="{{ old('name', $product->name) }}"
            required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Stock Quantity --}}
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
            value="{{ old('stock', $product->stock) }}"
            min="0"
            required>
          @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Current: {{ $product->stock }} units</small>
        </div>

        {{-- Rack Location --}}
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-location-dot me-1"></i>Rack Location
          </label>
          <select name="location" class="form-select @error('location') is-invalid @enderror">
            <option value="">-- Select Location --</option>
            <option value="A-01" {{ old('location', $product->location) == 'A-01' ? 'selected' : '' }}>A-01</option>
            <option value="A-02" {{ old('location', $product->location) == 'A-02' ? 'selected' : '' }}>A-02</option>
            <option value="A-03" {{ old('location', $product->location) == 'A-03' ? 'selected' : '' }}>A-03</option>
            <option value="B-05" {{ old('location', $product->location) == 'B-05' ? 'selected' : '' }}>B-05</option>
            <option value="B-06" {{ old('location', $product->location) == 'B-06' ? 'selected' : '' }}>B-06</option>
            <option value="C-01" {{ old('location', $product->location) == 'C-01' ? 'selected' : '' }}>C-01</option>
            <option value="C-02" {{ old('location', $product->location) == 'C-02' ? 'selected' : '' }}>C-02</option>
          </select>
          @error('location')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Description --}}
        <div class="col-12">
          <label class="form-label fw-bold">
            <i class="fa-solid fa-align-left me-1"></i>Description
            <span class="text-muted">(Optional)</span>
          </label>
          <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="3"
            placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Meta Info --}}
        <div class="col-12">
          <div class="alert alert-info">
            <small>
              <i class="fa-solid fa-clock me-1"></i>
              <strong>Created:</strong> {{ $product->created_at->format('d M Y, H:i') }} |
              <strong>Last Updated:</strong> {{ $product->updated_at->format('d M Y, H:i') }}
            </small>
          </div>
        </div>

        {{-- Buttons: Update + Cancel (ONLY for update form) --}}
        <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
          <div>
            <button type="submit" class="btn btn-warning px-4">
              <i class="fa-solid fa-save me-2"></i>Update Product
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary px-4 ms-2">
              <i class="fa-solid fa-times me-2"></i>Cancel
            </a>
          </div>
        </div>

      </div>
    </form>

    {{-- FORM DELETE PRODUCT (TERPISAH, TIDAK DI DALAM FORM UPDATE) --}}
    <div class="mt-3 text-end">
      <form action="{{ route('products.destroy', $product) }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Are you sure you want to delete this product?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger px-4">
          <i class="fa-solid fa-trash me-2"></i>Delete Product
        </button>
      </form>
    </div>

  </div>

</div>
@endsection
