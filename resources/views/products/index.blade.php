@extends('layouts.app')

@section('title', 'Inventory - WMS')

@section('content')
<div class="container mt-4 mb-5">

  <h3 class="mb-4 fw-bold">Inventory List</h3>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <!-- SEARCH BAR -->
    <div class="input-group shadow-sm" style="max-width: 500px;">
      <span class="input-group-text bg-dark text-white">
        <i class="fa-solid fa-magnifying-glass"></i>
      </span>
      <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Search inventory (SKU, name, location)...">
    </div>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus me-2"></i>Add New Product
    </a>
  </div>

  <!-- INVENTORY TABLE -->
  <div class="table-responsive bg-white rounded shadow-sm p-3">
    <table class="table table-striped table-hover mb-0" id="inventoryTable">
      <thead class="table-dark">
        <tr>
          <th class="text-center"><i class="fa-solid fa-barcode me-2"></i>SKU</th>
          <th class="text-center"><i class="fa-solid fa-box me-2"></i>Product Name</th>
          <th class="text-center"><i class="fa-solid fa-location-dot me-2"></i>Location</th>
          <th class="text-center"><i class="fa-solid fa-chart-column me-2"></i>Stock Quantity</th>
          <th class="text-center"><i class="fa-solid fa-gear me-2"></i>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          <td class="text-center">{{ $product->sku }}</td>
          <td class="text-center">{{ $product->name }}</td>
          <td class="text-center">{{ $product->location ?? 'N/A' }}</td>
          <td class="text-center">
            <span class="badge {{ $product->stock < 50 ? 'bg-danger' : 'bg-success' }}">
              {{ $product->stock }}
            </span>
          </td>
          <td class="text-center">
            <div class="btn-group" role="group">
              <a href="{{ route('products.edit', $product) }}"
                 class="btn btn-sm btn-warning"
                 title="Edit Product">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <button type="button"
                      class="btn btn-sm btn-danger"
                      onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')"
                      title="Delete Product">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
            <form id="delete-form-{{ $product->id }}"
                  action="{{ route('products.destroy', $product) }}"
                  method="POST"
                  style="display: none;">
              @csrf
              @method('DELETE')
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            <i class="fa-solid fa-box-open fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
            No products found. <a href="{{ route('products.create') }}">Add your first product</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products->count() > 0)
  <div class="mt-3 text-muted">
    <small>
      <i class="fa-solid fa-info-circle me-1"></i>
      Showing {{ $products->count() }} product(s)
    </small>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
  // SEARCH FUNCTION
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#inventoryTable tbody tr');

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  });

  // DELETE FUNCTION
  function deleteProduct(id, name) {
    if (confirm('Are you sure you want to delete "' + name + '"?')) {
      document.getElementById('delete-form-' + id).submit();
    }
  }
</script>
@endpush
