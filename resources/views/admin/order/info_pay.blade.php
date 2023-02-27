<div class="modal fade modal-slide-right" id="slideRightModalInfoPay{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="slideRightModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="slideRightModalLabel">
                    Información de pagos Orden #{{ $row->id }}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="form-group col-md-6">Método de pago</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">Pago en efectivo</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Tipo de compra</div>
                        @if($row->type == 1)
                        <div class="form-group col-md-5" style="float:right;text-align:right;">A domicilio</div>
                        @elseif($row->type == 2)
                        <div class="form-group col-md-5" style="float:right;text-align:right;">Recolectar en tienda</div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Total de venta</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$row->total }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Descuentos</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$row->discount }}</div>
                    </div>
                    
                    @if($row->type == 1)
                    <div class="row mb-3">
                        <div class="form-group col-md-6">Envio</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$row->d_charges }}</div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Retención de impuestos</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['reteneciones'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Comisión de plataforma</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['comisionxs'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6" style="color:green;">
                            Total a recibir<br />
                            <small>(Comisión de la plataforma + Retención de impuestos)</small>
                        </div>
                        <div class="form-group col-md-5" style="color:green;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['payment_to_admin'] }}</div>
                    </div>
                @else <!-- La venta fue con tarjeta -->
                    <div class="row mb-3">
                        <div class="form-group col-md-6">Método de pago</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">Medios electrónicos</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Total de venta</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$row->total }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Venta del negocio</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['total'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Descuentos</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$row->discount }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Retención de impuestos</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['reteneciones'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">Comisión de plataforma</div>
                        <div class="form-group col-md-5" style="float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['comisionxs'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6" style="color:red;">Saldo pendiente al repartidor</div>
                        <div class="form-group col-md-5" style="color:red;float:right;text-align:right;">{{ $currency.$row->d_charges }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6" style="color:red;">
                            Saldo pendiente al negocio<br />
                            <small>(Total de la venta - Retención de impuestos)</small>
                        </div>
                        <div class="form-group col-md-5" style="color:red;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['gananciasxt'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6" style="color:green;">
                            Ganancia por venta<br />
                            <small>(Comisión de la plataforma + Retención de impuestos)</small>
                        </div>
                        <div class="form-group col-md-5" style="color:green;float:right;text-align:right;">{{ $currency.$item->GetTaxes($row->id)['payment_to_admin'] }}</div>
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</div>