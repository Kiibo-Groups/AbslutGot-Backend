
<!-- Negocios -->
<div class="col-xxl-3 col-md-6">
    <div class="card info-card sales-card">

    <div class="card-body">
        <h5 class="card-title">Comercios</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bx bxs-store"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $overview['store'] }}</h6>
        </div>
        </div>
    </div>

    </div>
</div>
<!-- Negocios -->

<!-- Pedidos -->
<div class="col-xxl-3 col-md-6">
    <div class="card info-card revenue-card">
        <div class="card-body">
            <h5 class="card-title">Total de Pedidos</h5>

            <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bx bx-cart-alt"></i>
            </div>
            <div class="ps-3">
                <h6>{{ $overview['order'] }}</h6>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- Pedidos -->

<!-- Este mes -->
<div class="col-xxl-3 col-md-6">
    <div class="card info-card sales-card">
        <div class="card-body">
            <h5 class="card-title">Este Mes</h5>

            <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bx bxs-calendar-edit"></i>
            </div>
            <div class="ps-3">
                <h6>{{ $overview['month'] }}</h6>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- Este mes -->
 
<!-- Usuarios -->
<div class="col-xxl-3 col-md-6">
    <div class="card info-card customers-card">
        <div class="card-body">
            <h5 class="card-title">Usuarios Registrados</h5>

            <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bx bxs-user-check"></i>
            </div>
            <div class="ps-3">
                <h6>{{ $overview['user'] }}</h6>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- Usuarios -->