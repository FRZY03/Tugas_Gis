<nav class="sidebar bg-light border-end vh-100 d-flex flex-column p-3 position-fixed">
  <a href="{{ route('wilayah.index') }}" class="d-flex align-items-center mb-3 mb-md-0 text-decoration-none">
    <i class="fas fa-laugh-wink fa-lg me-2 text-primary"></i>
    <span class="fs-5 fw-bold">TUGAS GIS</span>
  </a>
  <hr>

  <ul class="nav nav-pills flex-column mb-auto">
    <li>
      <a href="{{ route('wilayah.index') }}" class="nav-link {{ request()->routeIs('wilayah.index') ? 'active' : 'text-dark' }}">
        <i class="fas fa-list me-2"></i> Peta
      </a>
    </li>

    <li>
      <a href="{{ route('wilayah.manage') }}" class="nav-link {{ request()->routeIs('wilayah.manage') ? 'active' : 'text-dark' }}">
        <i class="fas fa-chart-bar me-2"></i> Data Wilayah
      </a>
    </li>
  </ul>

  <hr>
</nav>
