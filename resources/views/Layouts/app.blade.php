<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') - TUGAS GIS</title>

  <!-- Bootstrap 5 CSS & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
    }

    .sidebar {
      width: 250px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
      }

      #main-content {
        margin-left: 0;
      }
    }

    #main-content {
      margin-left: 250px;
    }
  </style>
</head>
<body>

  <div class="d-flex">
    @include('layouts.sidebar') <!-- Sidebar -->

    <div id="main-content" class="flex-grow-1">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-3">
        <span class="navbar-brand fw-bold">PETA</span>
      </nav>

      <main class="p-4">
        @yield('content')
      </main>

      <footer class="bg-light text-center py-3 border-top mt-auto">
        © {{ date('Y') }} Tugas Gis. All rights reserved.
      </footer>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
