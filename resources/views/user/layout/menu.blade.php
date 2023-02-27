@php($page = Request::segment(1))
<ul class="sidebar-nav" id="sidebar-nav">

  <!-- Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link @if($page === 'home' || $page == 'setting') active @else collapsed @endif" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-grid"></i><span>Dashboard</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content @if($page === 'home' || $page == 'setting') active @else collapse @endif" data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ Asset(env('user').'/home') }}" class="@if($page === 'home') active @endif">
          <i class="bi bi-circle"></i><span>Inicio</span>
        </a>
      </li>
      <li>
        <a href="{{ Asset(env('user').'/setting') }}" class="@if( $page == 'setting') active @endif">
          <i class="bi bi-circle"></i><span>Configuración</span>
        </a>
      </li>
    </ul>
  </li>
  <!-- Dashboard Nav -->
  
  <!-- catalogo -->
  <li class="nav-item">
    <a class="nav-link @if($page === 'category' || $page == 'item' || $page == 'addon') active @else collapsed @endif" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-card-list"></i>
      <span>
        @if(Auth::user()->subtype == 0)
            Elementos de menú
          @else 
            Elementos de catalogo
          @endif
      </span>
      <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="forms-nav" class="nav-content @if($page === 'category' || $page == 'item' || $page == 'addon') active @else collapse @endif" data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ Asset(env('user').'/category') }}" class="@if($page === 'category') active @endif">
          <i class="bi bi-circle"></i><span>Categoría</span>
        </a>
      </li>
      <li>
        <a href="{{ Asset(env('user').'/item') }}" class="@if($page == 'item') active @endif">
          <i class="bi bi-circle"></i><span>Productos</span>
        </a>
      </li> 
      <li>
        <a href="{{ Asset(env('user').'/addon') }}" class="@if($page == 'addon') active @endif">
          <i class="bi bi-circle"></i><span>Complementos</span>
        </a>
      </li>
    </ul>
  </li>
  <!-- catalogo -->

  <!-- Gestion de pedidos -->
  <?php 
  $cOrder = DB::table('orders')->where('store_id',Auth::user()->id)->where('status',0)->count(); 
  $rOrder = DB::table('orders')->where('store_id',Auth::user()->id)->whereIn('status',[1,1.5,3,4])->count(); 
  ?>
  <li class="nav-item">
    <a class="nav-link @if($page === 'order') active @else collapsed @endif" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-clipboard-check"></i>
      <span>
        Gestionar pedidos
        @if($cOrder > 0) 
          <span class="badge bg-primary text-white">{{ $cOrder }}</span>
        @endif
      </span>
      <i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="tables-nav" class="nav-content @if($page === 'order') active @else collapse @endif" data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ Asset('order?status=0') }}" class=" @if($page === 'order?status=0') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Nuevos pedidos
            @if($cOrder > 0) 
            &nbsp;&nbsp;<span class="badge bg-success text-white">{{ $cOrder }}</span>
            @endif
          </span>
        </a>
      </li> 
      <li>
        <a href="{{ Asset('order?status=1') }}" class=" @if($page === 'order?status=1') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Pedidos en curso
            @if($rOrder > 0) 
            &nbsp;&nbsp;<span class="badge bg-primary text-white">{{ $rOrder }}</span>
            @endif
          </span>
        </a>
      </li> 
      <li>
        <a href="{{ Asset('order?status=2') }}" class=" @if($page === 'order?status=2') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Pedidos cancelados
          </span>
        </a>
      </li> 
      <li>
        <a href="{{ Asset('order?status=5') }}" class=" @if($page === 'order?status=5') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Pedidos completados
          </span>
        </a>
      </li> 
    </ul>
  </li>
  <!-- Gestion de pedidos -->

  <!-- Reportes --> 
  <li class="nav-item">
    <a class="nav-link @if($page === 'report') active @else collapsed @endif" href="{{ Asset(env('user').'/report') }}">
      <i class="bi bi-file-earmark-bar-graph-fill"></i>
      <span>Reportes</span>
    </a>
  </li>
  <!-- Reportes --> 
  
  <!-- Cerrar sesion --> 
  <li class="nav-item">
    <a class="nav-link collapsed" href="{{ Asset(env('user').'/logout') }}">
      <i class="bi bi-toggle-off"></i>
      <span>Cerrar sesión</span>
    </a>
  </li>
  <!-- Cerrar sesion --> 
</ul>