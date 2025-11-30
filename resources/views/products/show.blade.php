@extends('layouts.app')

@section('title', 'Product Detail - WMS')

@section('content')
<div class="container mt-4 mb-4">

  <div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-2"></i>Back to Inventory
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fa-solid fa-box me-2"></i>Product Details</h4>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th width="200">SKU</th>
          <td>{{ $product->sku }}</td>
        </tr>
        <tr>
          <th>Product Name</th>
          <td>{{ $product->name }}</td>
        </tr>
        <tr>
          <th>Stock</th>
          <td>
            <span class="badge {{ $product->stock < 50 ? 'bg-danger' : 'bg-success' }}">
              {{ $product->stock }}
            </span>
          </td>
        </tr>
        <tr>
          <th>Location</th>
          <td>{{ $product->location ?? 'N/A' }}</td>
        </tr>
        <tr>
          <th>Description</th>
          <td>{{ $product->description ?? '-' }}</td>
        </tr>
        <tr>
          <th>Created At</th>
          <td>{{ $product->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
          <th>Last Updated</th>
          <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
        </tr>
      </table>

      <div class="mt-3">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
          <i class="fa-solid fa-pen-to-square me-2"></i>Edit Product
        </a>
        <form action="{{ route('products.destroy', $product) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Are you sure?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash me-2"></i>Delete Product
          </button>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection
