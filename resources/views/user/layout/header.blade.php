@php($page = Request::segment(1))
<div class="d-flex align-items-center justify-content-between">
    <a href="{{ Asset(env('user').'/home') }}" class="logo d-flex align-items-center">
      <img src="{{Asset('assets/img/logo.png')}}" alt="">
      <span class="d-none d-lg-block">Bienvenido(a)</span>
    </a>
    @if($page != 'kitchen_orders')
    <i class="bi bi-list toggle-sidebar-btn"></i>
    @endif
  </div><!-- End Logo -->

  @if($page != 'kitchen_orders')
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ Asset(env('user').'/home') }}" class="logo d-flex align-items-center"> 
      &nbsp;<span class="d-none d-lg-block">Panel de control</span>
    </a> 
  </div> 
  @endif
  <!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      
       
      <!-- Notification Nav -->
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" id="notifications"  data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          @if($orders_count > 0)
          <span class="badge bg-primary badge-number">{{$orders_count}}</span>
          @endif
        </a><!-- End Notification Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          @if($orders_count > 0)
            <li class="dropdown-header">
              Actualmente tienes {{$orders_count}} notificaciones nuevas.
            </li>

            @foreach($orders as $row)
            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="notification-item">
              <i class="bi bi-patch-check-fill text-success"></i>
              <a href="https://bincar.kiibo.mx/order?status=0">
                <div>
                  <h4>🎉 Nuevo pedido recibido 🎉</h4>
                  <p>
                    <b>#{{$row->id}}</b> - Valor del pedido <b>${{number_format($row->total,2)}}</b> <br />
                    
                    @if($row->type == 1)
                        <td width="15%">{{ $row->address }}</td>
                    @elseif($row->type == 2)
                        <td width="15%">El usuario pasara a recoger el pedido</td>
                    @elseif($row->type == 3)
                        <td width="15%">
                            Pedido en mesa <h4><b>#{{$row->mesa}}</b></h4>
                        </td>
                    @endif
                  </p>
                  <p>{{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</p>
                </div>
              </a>
            </li>
            @endforeach
          @else 
            <li class="dropdown-header">
              Actualmente no tiene notificaciones nuevas.
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
          @endif
        </ul><!-- End Notification Dropdown Items -->
      </li>
      <!-- End Notification Nav -->

      <!-- Profile Nav -->
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="{{Asset('upload/user/'.Auth::user()->img)}}" alt="Profile" class="rounded-circle" style="width: 35px;height: 35px;">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ substr(Auth::user()->name,0,11) }}</span>
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
            <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('user').'/home') }}">
              <i class="bi bi-person"></i>
              <span>Inicio</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
  
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('user').'/setting') }}">
              <i class="bi bi-gear"></i>
              <span>Configuraciones</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
  
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ Asset('order?status=0') }}">
              <i class="bi bi-cart-check-fill"></i>
              <span>Pedidos</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
  
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ Asset(env('user').'/logout') }}">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
  
        </ul><!-- End Profile Dropdown Items -->
      </li>
      <!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->