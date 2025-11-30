@extends('layouts.app')

@section('title', 'Dashboard - WMS')

@section('content')
<div class="container mt-4">

  <!-- DASHBOARD OVERVIEW -->
  <section class="mb-5">
    <h2 class="mb-4 fw-bold text-dark">Dashboard Overview</h2>

    <div class="row g-4">

      <!-- KPI Card 1: Total Products -->
      <div class="col-md-4">
        <div class="card card-custom-blue shadow-sm rounded-3">
          <div class="card-body text-center py-3">
            <i class="fa-solid fa-box kpi-icon"></i>
            <p class="kpi-label mt-2 mb-0">Total Products</p>
            <p class="kpi-value mb-0">{{ $totalProducts }}</p>
            <small class="kpi-subtitle">items in system</small>
          </div>
        </div>
      </div>

      <!-- KPI Card 2: Stock Utilization -->
      <div class="col-md-4">
        <div class="card card-custom-green shadow-sm rounded-3">
          <div class="card-body text-center py-3">
            <i class="fa-solid fa-chart-pie kpi-icon"></i>
            <p class="kpi-label mt-2 mb-0">Stock Utilization</p>
            <p class="kpi-value mb-0">{{ $stockUtilization }}%</p>
            <small class="kpi-subtitle">{{ $totalStock }}/1000 units</small>
          </div>
        </div>
      </div>

      <!-- KPI Card 3: Low Stock Alert -->
      <div class="col-md-4">
        <div class="card card-custom-orange shadow-sm rounded-3">
          <div class="card-body text-center py-3">
            <i class="fa-solid fa-triangle-exclamation kpi-icon"></i>
            <p class="kpi-label mt-2 mb-0">Low Stock Alert</p>
            <p class="kpi-value mb-0">{{ $lowStockCount }}</p>
            <small class="kpi-subtitle">items below 50 units</small>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- CURRENT INVENTORY TABLE -->
  <section class="mb-5">
    <h2 class="mb-3 fw-bold text-dark">Current Inventory</h2>

    <!-- SEARCH BAR -->
    <div class="input-group mb-3 shadow-sm">
      <span class="input-group-text bg-dark text-white">
        <i class="fa-solid fa-magnifying-glass"></i>
      </span>
      <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Search inventory (SKU, name, stock)...">
    </div>

    <div class="table-responsive bg-white rounded shadow-sm p-3">
      <table class="table table-striped table-hover mb-0" id="inventoryTable">
        <thead class="table-dark">
          <tr>
            <th class="text-center"><i class="fa-solid fa-barcode me-2"></i>SKU</th>
            <th class="text-center"><i class="fa-solid fa-box me-2"></i>Product Name</th>
            <th class="text-center"><i class="fa-solid fa-chart-column me-2"></i>Stock Quantity</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentProducts as $product)
          <tr>
            <td class="text-center">{{ $product->sku }}</td>
            <td class="text-center">{{ $product->name }}</td>
            <td class="text-center">
              <span class="badge {{ $product->stock < 50 ? 'bg-danger' : 'bg-success' }}">
                {{ $product->stock }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="text-center text-muted py-3">
              No products found. <a href="{{ route('products.create') }}">Add your first product</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <!-- ADD NEW PRODUCT FORM -->
  <section class="mb-5">
    <h2 class="mb-3 fw-bold text-dark">Add New Product</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Oops!</strong> There were some problems with your input:
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="bg-white rounded shadow-sm p-4">
      <form action="{{ route('products.store') }}" method="POST" class="row g-3">
        @csrf

        <div class="col-md-4">
          <label class="form-label fw-bold">SKU <span class="text-danger">*</span></label>
          <input
            type="text"
            name="sku"
            class="form-control @error('sku') is-invalid @enderror"
            placeholder="Enter SKU (e.g., P001)"
            value="{{ old('sku') }}"
            required>
          @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
          <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Enter product name"
            value="{{ old('name') }}"
            required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
          <input
            type="number"
            name="stock"
            class="form-control @error('stock') is-invalid @enderror"
            placeholder="Enter quantity"
            value="{{ old('stock', 0) }}"
            min="0"
            required>
          @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 mt-3">
          <button type="submit" class="btn btn-danger px-4">
            <i class="fa-solid fa-paper-plane me-2"></i>Submit Product
          </button>
          <a href="{{ route('products.index') }}" class="btn btn-secondary px-4 ms-2">
            <i class="fa-solid fa-list me-2"></i>View All Inventory
          </a>
        </div>

      </form>
    </div>
  </section>

</div>
@endsection

@push('scripts')
<script>
  // SEARCH FUNCTION
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      const query = this.value.toLowerCase();
      const rows = document.querySelectorAll('#inventoryTable tbody tr');

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }
</script>
@endpush
