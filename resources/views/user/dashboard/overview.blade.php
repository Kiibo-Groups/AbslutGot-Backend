 <!-- Sales Card -->
 <div class="col-xxl-3 col-md-6">
    <div class="card info-card sales-card">

    <div class="card-body">
        <h5 class="card-title">Articulos totales</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-basket-fill"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $overview['items'] }}</h6>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Sales Card -->

<!-- Revenue Card -->
<div class="col-xxl-3 col-md-6">
    <div class="card info-card revenue-card">

    <div class="card-body">
        <h5 class="card-title">Pedidos totales</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $overview['order'] }}</h6>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Revenue Card -->

<!-- Órdenes completada -->
<div class="col-xxl-3 col-xl-12">

    <div class="card info-card customers-card">

    <div class="card-body">
        <h5 class="card-title">Órdenes completadas</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ui-checks"></i>
        </div>
        <div class="ps-3">
            <h6>
                {{ $overview['complete'] }}
            </h6>
        </div>
        </div>

    </div>
    </div>

</div><!-- End Órdenes completada -->

<!-- Pedidos de este mes -->
<div class="col-xxl-3 col-xl-12">

    <div class="card info-card customers-card">

    <div class="card-body">
        <h5 class="card-title">Pedidos de este mes</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-bar-chart"></i>
        </div>
        <div class="ps-3">
            <h6>
                {{ $overview['month'] }}
            </h6>
        </div>
        </div>

    </div>
    </div>

</div><!-- End Pedidos de este mes -->