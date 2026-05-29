<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Panel | Anime Portal')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Theme style (AdminLTE via CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">На сайт</a>
      </li>
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="fas fa-sign-out-alt"></i> Вийти
            </a>
        </form>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('anime.create') }}" class="brand-link">
      <span class="brand-text font-weight-light">Anime CMS Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">КОНТЕНТ</li>
          <li class="nav-item">
            <a href="{{ route('anime.create') }}" class="nav-link">
              <i class="nav-icon fas fa-plus-square"></i>
              <p>Додати аніме</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('anime.admin_index') }}" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>Список аніме</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.genres.index') }}" class="nav-link">
              <i class="nav-icon fas fa-tags"></i>
              <p>Жанри</p>
            </a>
          </li>
          <li class="nav-header">КОРИСТУВАЧІ</li>
          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Список користувачів</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Скарги</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('page_title')</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              {{ session('success') }}
          </div>
      @endif

      @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              {{ session('error') }}
          </div>
      @endif

      @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <ul class="mb-0">
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Anime Portal CMS</strong>
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
