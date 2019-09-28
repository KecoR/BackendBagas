<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin Panel</title>
  
  

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/weather-icon/css/weather-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.csss') }}">
  @yield('css')

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{ asset('image/users/'.Auth::user()->image) }}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->full_name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('home') }}">Admin Panel</a>
          </div>
          {{-- <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
          </div> --}}
          <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="dropdown">
                @if (\Auth::user()->role_id === 1)
                    <a href="{{ route('users') }}" class="nav-link"><i class="far fa-user"></i><span>Kelola User</span></a>
                    <a href="{{ route('roles') }}" class="nav-link"><i class="fas fa-user-tag"></i><span>Kelola Role</span></a>
                    <a href="{{ route('museums') }}" class="nav-link"><i class="fas fa-hotel"></i><span>Kelola Museum</span></a>
                    <a href="#" class="nav-link"><i class="fas fa-box"></i><span>Kelola Order</span></a>
                @endif
            </li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          @if (\Route::current()->getName() == 'home')
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                @if (\Auth::user()->role_id === 1)
                  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1">
                          <a href="#">
                            <div class="card-icon bg-primary">
                              <i class="far fa-user"></i>
                            </div>
                          </a>
                          <div class="card-wrap">
                          <div class="card-header">
                              <h4>User</h4>
                          </div>
                          <div class="card-body">
                            @php
                                $user = \App\User::all();
      
                                echo $user->count();
                            @endphp
                          </div>
                          </div>
                      </div>
                  </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <a href="#">
                          <div class="card-icon bg-danger">
                            <i class="fas fa-user-tag"></i>
                          </div>
                        </a>
                        <div class="card-wrap">
                        <div class="card-header">
                            <h4>Role</h4>
                        </div>
                        <div class="card-body">
                          
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <a href="#">
                          <div class="card-icon bg-warning">
                            <i class="fas fa-hotel"></i>
                            </div>
                        </a>
                        <div class="card-wrap">
                        <div class="card-header">
                            <h4>Museum</h4>
                        </div>
                        <div class="card-body">
                          
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <a href="#">
                            <div class="card-icon bg-success">
                              <i class="fas fa-box"></i>
                            </div>
                        </a>
                        <div class="card-wrap">
                        <div class="card-header">
                            <h4>Order</h4>
                        </div>
                        <div class="card-body">
                          
                        </div>
                        </div>
                    </div>
                    </div> 
                @endif       
            </div>
          @else
              @yield('content')
          @endif
        </section>
      </div>
      <footer class="main-footer">
        
        <div class="footer-right">
          
        </div>
      </footer>
    </div>
  </div>

  @yield('modal')

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/popper.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  
  <!-- JS Libraies -->
  <script src="{{ asset('assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
  <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
  <script src="{{ asset('assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
  <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
  <script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
  <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
  @yield('js')

  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/index-0.js') }}"></script>
  
  <!-- Template JS File -->
  @yield('footer-scripts')
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>