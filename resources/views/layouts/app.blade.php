<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'WMS System')</title>

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet">

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
        <i class="fa-solid fa-warehouse me-2"></i>WMS System
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
              <i class="fa-solid fa-chart-line me-1"></i>Dashboard
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
               href="{{ route('products.index') }}">
              <i class="fa-solid fa-boxes-stacked me-1"></i>Inventory
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('inbound.*') ? 'active' : '' }}"
               href="{{ route('inbound.index') }}">
              <i class="fa-solid fa-arrow-down-wide-short me-1"></i>Inbound
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('outbound.*') ? 'active' : '' }}"
               href="{{ route('outbound.index') }}">
              <i class="fa-solid fa-arrow-up-wide-short me-1"></i>Outbound
            </a>
          </li>

          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user me-1"></i>{{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                  <i class="fa-solid fa-gear me-2"></i>Profile
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                  </button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main class="flex-grow-1">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; PAD Pamela Rizqi Maharani 2025 - Warehouse Management System</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
