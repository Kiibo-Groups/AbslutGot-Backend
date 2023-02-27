@inject('admin', 'App\Admin')
@php($page = Request::segment(1)) 
<ul class="sidebar-nav" id="sidebar-nav">

  <!-- Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link @if($page === 'home' || $page == 'setting') active @else collapsed @endif" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-grid"></i><span>Dashboard</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content @if($page === 'home' || $page == 'setting' || $page == 'category' || $page == 'text' || $page == 'page') active @else collapse @endif" data-bs-parent="#sidebar-nav">
      
		@if($admin->hasPerm('Dashboard - Inicio'))
		<li>
			<a href="{{ Asset(env('admin').'/home') }}" class="@if($page === 'home') active @endif">
			<i class="bi bi-circle"></i><span>Inicio</span>
			</a>
		</li>
		@endif

		@if($admin->hasPerm('Dashboard - Configuraciones'))
		<li>
			<a href="{{ Asset(env('admin').'/setting') }}" class="@if( $page == 'setting') active @endif">
			<i class="bi bi-circle"></i><span>Configuración</span>
			</a>
		</li>
		@endif
 
		@if($admin->hasPerm('Dashboard - Textos de la aplicacion'))
		<li>
			<a href="{{ Asset(env('admin').'/text/add') }}" class="@if( $page == 'text') active @endif">
				<i class="bi bi-circle"></i><span>Texto de la aplicación</span>
			</a>
		</li> 
		@endif

		@if($admin->hasPerm('Paginas de la aplicacion'))
		<li>
			<a href="{{ Asset(env('admin').'/page/add') }}" class="@if( $page == 'page') active @endif">
				<i class="bi bi-circle"></i><span>Páginas de aplicaciones</span>
			</a>
		</li> 
		@endif
    </ul>
  </li>
  <!-- Dashboard Nav -->
  
  <!-- Banners -->
  @if($admin->hasPerm('Banners'))
  <li class="nav-item">
    <a class="nav-link  @if($page === 'banner') active @else collapsed @endif" href="{{ Asset(env('admin').'/banner') }}">
      <i class="bx bxs-image"></i>
      <span>Banners</span>
    </a>
  </li>
  @endif
  <!-- Banners -->

  <!-- Categorias -->
  @if($admin->hasPerm('Dashboard - Categorias'))
  <li class="nav-item">
    <a class="nav-link @if( $page == 'category') active @else collapsed @endif" href="{{ Asset(env('admin').'/category') }}">
      <i class="bi bi-file-earmark-easel-fill"></i>
      <span>Categorias</span>
    </a>
  </li> 
  @endif
  <!-- Categorias -->

  <!-- Ciudades -->
  @if($admin->hasPerm('Administrar Ciudades'))
  <li class="nav-item">
    <a class="nav-link  @if($page === 'city') active @else collapsed @endif" href="{{ Asset(env('admin').'/city') }}">
      <i class="bx bxs-edit-location"></i>
      <span>Administrar ciudades</span>
    </a>
  </li>
  @endif
  <!-- Ciudades -->

  <!-- Negocios -->
  @if($admin->hasPerm('Adminisrtar Restaurantes'))
  <li class="nav-item">
    <a class="nav-link  @if($page === 'user') active @else collapsed @endif" href="{{ Asset(env('admin').'/user') }}">
      <i class="bx bxs-store-alt"></i>
      <span>Administrar negocios</span>
    </a>
  </li>
  @endif
  <!-- Negocios -->

  <!-- Ofertas de descuento -->
  @if($admin->hasPerm('Ofertas de descuento'))
  <li class="nav-item">
    <a class="nav-link  @if($page === 'offer') active @else collapsed @endif" href="{{ Asset(env('admin').'/offer') }}">
      <i class="bx bxs-offer"></i>
      <span>Ofertas de descuento</span>
    </a>
  </li>

  @endif
  <!-- Ofertas de descuento -->

  <!-- Gestion de pedidos -->
  <?php 
  $cOrder = DB::table('orders')->where('status',0)->count(); 
  $rOrder = DB::table('orders')->whereIn('status',[1,1.5,3,4])->count(); 
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
        <a href="{{ Asset(env('admin').'/order?status=0') }}" class=" @if($page === 'order?status=0') active @endif">
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
        <a href="{{ Asset(env('admin').'/order?status=1') }}" class=" @if($page === 'order?status=1') active @endif">
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
        <a href="{{ Asset(env('admin').'/order?status=2') }}" class=" @if($page === 'order?status=2') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Pedidos cancelados
          </span>
        </a>
      </li> 
      <li>
        <a href="{{ Asset(env('admin').'/order?status=5') }}" class=" @if($page === 'order?status=5') active @endif">
          <i class="bi bi-circle"></i>
          <span>
            Pedidos completados
          </span>
        </a>
      </li> 
    </ul>
  </li>
  <!-- Gestion de pedidos -->
  
  <!-- Reporte de ventas -->
  @if($admin->hasPerm('Reportes de ventas'))
  <li class="nav-item">
    <a class="nav-link collapsed" href="{{ Asset(env('admin').'/report') }}">
      <i class="bi bi-file-earmark-easel-fill"></i>
      <span>Reporte de ventas</span>
    </a>
  </li>
  @endif
  <!-- Reporte de ventas -->

  <!-- Cerrar sesion --> 
  <li class="nav-item">
    <a class="nav-link collapsed" href="{{ Asset(env('admin').'/logout') }}">
      <i class="bi bi-toggle-off"></i>
      <span>Cerrar sesión</span>
    </a>
  </li>
  <!-- Cerrar sesion -->

</ul>