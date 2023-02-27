@extends('user.layout.main')

@section('title') {{ $title }} @endsection
@section('breadcrumb') Gestor de pedidos @endsection


@section('content')

<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr> 
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Dirección/Mesa</th> 
                                <th>Elementos</th>
                                <th style="text-align: right">Opciones</th>
                            </tr> 
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            @include('user.order.info_pay')
                            <tr> 
                                <td>
                                    <a href="https://bincar.kiibo.mx/track_order/?id={{$row->external_id}}" target="_blank">
                                        <img src="data:image/png;base64,{{ $row->code_order }}" style="width:50px;height: 50px;max-width:none !important;">
                                    </a> 
                                </td>
                                <td>{{ $row->name }}<br>{{ $row->phone }}</td>
                                @if($row->type == 1)
                                    <td width="15%">{{ $row->address }},{{ $row->city }}</td>
                                @elseif($row->type == 2)
                                    <td width="15%">El usuario pasara a recoger el pedido</td>
                                @elseif($row->type == 3)
                                    <td width="15%">
                                        Pedido en mesa <h4><b>#{{$row->mesa}}</b></h4>
                                    </td>
                                @endif
                                <td width="50%">     
                                    <div class="row">
                                        <div class="col-md-6"><b>Elemento</b></div>
                                        <div class="col-md-3"><b>Cantidad</b></div>
                                        <div class="col-md-3"><b>Precio</b></div>
                                    </div><hr>

                                    @foreach($item->getItem($row->id) as $i)

                                    <div class="row" style="font-size: 12px">
                                        <div class="col-md-6">{{$i['item'] }}</div>
                                        <div class="col-md-3">x{{ $i['qty'] }}</div>
                                        <div class="col-md-3">{{ $currency.$i['price'] }}</div>
                                    </div><hr>

                                    @if(count($item->getAddon($i['cart_no'],$row->id)) > 0)
                                        @foreach($item->getAddon($i['cart_no'],$row->id) as $add)
                                            <div class="row" style="font-size: 12px">
                                                <div class="col-md-6">{{ $add->addon }}</div>
                                                <div class="col-md-3">x{{ $add->qty }}</div>
                                                <div class="col-md-3">{{ $currency.$add->price }}</div>
                                            </div><hr>
                                        @endforeach
                                    @endif

                                    @endforeach

                                    <div class="row">
                                        <div class="col-md-12">Total a recibir <br />
                                        @if($row->payment_method == 1) <!-- La venta fue en efectivo -->
                                            <h3 style="color:green;">{{ $currency.$item->GetTaxes($row->id)['payment_to_receive'] }}</h3>
                                        @else
                                            <h3 style="color:green;">{{ $currency.$item->GetTaxes($row->id)['gananciasxt'] }}</h3>
                                        @endif

                                        <button href="javascript::void()" data-bs-toggle="modal" data-bs-target="#slideRightModalInfoPay{{ $row->id }}" class="btn btn-secondary">
                                            Desglose de información
                                        </button>
                                        </div>
                                    </div>
                                    @if($row->notes)
                                        <small style="color:blue">Notas : {{ $row->notes }}</small>
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    @include('user.order.action')
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {!! $data->links() !!}
    </div>
</div>
@endsection