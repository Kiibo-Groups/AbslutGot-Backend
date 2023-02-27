
<div class="modal fade modal-slide-right" id="slideRightModalInfoPay{{ $row->id }}" 
    tabindex="-1" role="dialog" 
    aria-labelledby="slideRightModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="slideRightModalLabel">
                    Información de pagos Orden #{{ $row->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="form-group col-md-6">Fecha de creación</div>
                    <div class="form-group col-md-5" style="float:right;text-align:right;">
                        {{ $row->created_at->diffForHumans(); }}
                    </div>
                </div>
                
                @if($row->payment_method == 1) <!-- La venta fue en efectivo -->
                    <div class="row mb-3">
                        <div class="col-md-6">Método de pago</div>
                        <div class="col-md-5" style="float:right;text-align:right;">Pago en efectivo</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">Total de la venta</div>
                        <div class="col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['total'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">Comisión de la plataforma</div>
                        <div class="col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['comisionxs'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">Retención de impuestos</div>
                        <div class="col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['reteneciones'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6" style="color:green;">
                            Total a recibir<br />
                            <small>(Total de venta + Comisión de plataforma)</small>
                        </div>
                        <div class="col-md-5" style="color:green;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['payment_to_receive'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6" style="color:red;">
                            Saldo pendiente a la plataforma<br />
                            <small>(Comisión de la plataforma + Retención de impuestos)</small>
                        </div>
                        <div class="col-md-5" style="color:red;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['payment_to_admin'] }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6" style="color:green;">Tú ganancia por venta</div>
                        <div class="col-md-5" style="color:green;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['gananciasxt'] }}</div>
                    </div>
                @else <!-- La venta fue con tarjeta -->
                    <div class="row">
                        
                        <div class="col-md-6">Método de pago</div>
                        <div class="col-md-5" style="float:right;text-align:right;">Medios electrónicos</div>

                        <div class="col-md-6">Total de venta</div>
                        <div class="col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['total'] }}</div>
                        
                        <div class="col-md-6">Retención de impuestos</div>
                        <div class="col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['reteneciones'] }}</div>

                        <div class="col-md-6" style="color:green;">
                            Total a recibir<br />
                            <small>(Total de venta - Retención de impuestos)</small>
                        </div>
                        <div class="col-md-5" style="color:green;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['gananciasxt'] }}</div>
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>