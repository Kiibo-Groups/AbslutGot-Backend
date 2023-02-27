<div class="d-flex align-items-center justify-content-between">
    <a href="{{ Asset(env('admin').'/home') }}" class="logo d-flex align-items-center">
      <img src="{{Asset('assets/img/logo.png')}}" alt="">
      <span class="d-none d-lg-block">{{ Auth::guard('admin')->user()->name }}</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<div class="d-flex align-items-center justify-content-between">
  <a href="{{ Asset(env('admin').'/home') }}" class="logo d-flex align-items-center"> 
    &nbsp;<span class="d-none d-lg-block">Panel de control</span>
  </a> 
</div> 

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item dropdown">

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        {{-- <span class="badge bg-primary badge-number">4</span> --}}
      </a><!-- End Notification Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
          Aún no tienes notificaciones
          {{-- <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a> --}}
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        {{-- <li class="notification-item">
          <i class="bi bi-exclamation-circle text-warning"></i>
          <div>
            <h4>Lorem Ipsum</h4>
            <p>Quae dolorem earum veritatis oditseno</p>
            <p>30 min. ago</p>
          </div>
        </li> 
        <li>
          <hr class="dropdown-divider">
        </li> --}}

      </ul><!-- End Notification Dropdown Items -->

    </li><!-- End Notification Nav -->

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="{{Asset('assets/img/logo.png')}}" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::guard('admin')->user()->name }}</span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6>Bienvenido(a)</h6>
          <span>Panel de control</span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('admin').'/home') }}">
            <i class="bi bi-person"></i>
            <span>Inicio</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('admin').'/setting') }}">
            <i class="bi bi-gear"></i>
            <span>Configuraciones</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('admin').'order?status=0') }}">
            <i class="bi bi-cart-check-fill"></i>
            <span>Pedidos</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('admin').'/logout') }}">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->