<div class="modal fade modal-slide-right" id="viewModalElements{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="slideRightModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="slideRightModalLabel">
                    Elementos del pedido #{{ $row->id }}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6"><b>Elemento</b></div>
                    <div class="col-md-3"><b>Cantidad</b></div>
                    <div class="col-md-3"><b>Precio</b></div>
                </div>
                <hr>

                @foreach($item->getItem($row->id) as $i)

                <div class="row" style="font-size: 13px;font-weight:800;">
                <div class="col-md-6">{{$i['item'] }}</div>
                <div class="col-md-3">{{ $i['qty'] }}</div>
                <div class="col-md-3">{{ $currency.$i['price'] }}</div>
                </div><hr>

                    @if(count($item->getAddon($i['cart_no'],$row->id)) > 0)

                    <div class="row" style="font-size: 12px">
                        <div class="col-md-6">Complementos</div>
                    </div><hr>

                        @foreach($item->getAddon($i['cart_no'],$row->id) as $add)

                        <div class="row" style="font-size: 12px">
                        <div class="col-md-6"><small style="color:blue;font-size:11px;margin-left:10px;">{{ $add->addon }}</small></div>
                        <div class="col-md-3">{{ $add->qty }}</div>
                        <div class="col-md-3">{{ $currency.$add->price }}</div>
                        </div><hr>

                        @endforeach

                    @endif

                @endforeach

                <div class="row">
                    <div class="col-md-12">
                        <!--
                        Total <br />
                        <h3 style="color:green;">
                            {{ $currency.$item->GetTaxes($row->id)['payment_to_admin'] }}
                        </h3>-->
                        <button href="javascript::void()" data-bs-toggle="modal" data-bs-target="#slideRightModalInfoPay{{ $row->id }}" class="btn btn-primary">
                            Desglose de información
                        </button>
                    </div>
                </div><hr>

                @if($row->notes)
                <small style="color:blue">Notas : {{ $row->notes }}</small>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</div>