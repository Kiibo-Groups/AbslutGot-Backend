@extends('admin.layout.main')

@section('title') Imprimir Factura @endsection
@section('breadcrumb') Factura @endsection


@section('content') 
<div class="col-lg-10 mx-auto">
    <div class="row">
        <div class="card">
            <div class="card-body py-3 m-b-30">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <address class="m-t-10">
                            Para,<br>
                            <span class="h4 font-primary"> {{ $order->name }}</span> <br>
                            {{ $order->phone }}<br />
                            {{ $order->email }}<br />
                            <a href="https://maps.google.com/?q={{ $order->address }}" target="_blank" style="color:blue;">{{ $order->address }}</a><br />
                            {{ $order->city }}<br />
                        </address>
                    </div>
                    <div class="col-md-6 my-auto" style="text-align:right;">
                        <h1 class="font-primary">Recibo</h1>
                        <div class="">Order ID: #{{ $order->id }}</div>
                        <div class="">Fecha: {{ date('d-M-Y',strtotime($order->created_at)) }}</div>
                    </div>
                </div>

                <div class="table-responsive ">
                    <table class="table m-t-50">
                        <thead>
                            <tr>
                                <th width="40%">Elemento</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total = [])
                            @foreach($items as $item)
                                @php($total[] = $item['qty'] * $item['price'])
                                <tr>
                                    <td width="40%">{{ $item['type'] }} - {{ $item['item'] }}</td>
                                    <td width="20%" class="text-center">{{ $item['price'] }}</td>
                                    <td width="20%" class="text-center">{{ $item['qty'] }}</td>
                                    <td width="20%" style="text-align:right;">{{ $currency.$item['qty'] * $item['price'] }}</td>
                                </tr>
                                @foreach($it->getAddon($item['id'],$order->id) as $add)
                                    <tr>
                                        <td width="40%">{{ $add->addon }}</td>
                                        <td width="20%" class="text-center">{{ $currency.$add->price }}</td>
                                        <td width="20%" class="text-center">{{ $add->qty  }}</td>
                                        <td width="20%" style="text-align:right;">{{ $currency.$add->qty * $add->price }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr>
                                <td width="40%">&nbsp;</td>
                                <td width="20%">&nbsp;</td>
                                <td width="20%" class="text-center"><b>Total</b></td>
                                <td width="20%" style="text-align:right;"><b>{{ $currency.array_sum($total) }}</b></td>
                            </tr>

                            @if($order->discount)
                                <tr>
                                    <td width="40%">&nbsp;</td>
                                    <td width="20%">&nbsp;</td>
                                    <td width="20%" class="text-center"><b>Descuentos</b></td>
                                    <td width="20%" style="text-align:right;"><b>{{ $currency.$order->discount }}</b></td>
                                </tr>
                            @endif


                            @if($order->d_charges)
                                <tr>
                                    <td width="40%">&nbsp;</td>
                                    <td width="20%">&nbsp;</td>
                                    <td width="20%" class="text-center"><b>Cargos de envio</b></td>
                                    <td width="20%" style="text-align:right;"><b>{{ $currency.$order->d_charges }}</b></td>
                                </tr>
                            @endif

                            @if($order->t_charges)
                                <tr>
                                    <td width="40%">&nbsp;</td>
                                    <td width="20%">&nbsp;</td>
                                    <td width="20%" class="text-center"><b>Comisión por servicio</b></td>
                                    <td width="20%" style="text-align:right;"><b>{{ $currency.$order->t_charges }}</b></td>
                                </tr>
                            @endif

                            <tr>
                                <td width="40%">&nbsp;</td>
                                <td width="20%">&nbsp;</td>
                                <td width="20%" class="text-center"><b>Sub Total</b></td>
                                <td width="20%" style="text-align:right;"><b>{{ $currency.$order->total }}</b></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                
                <div class="p-t-10 p-b-20">
                    <p class="text-muted ">
                        @if($order->payment_method == 1)
                        <b>Metodo de pago: </b> Efectivo<br><br>
                        @elseif($order->payment_method == 2)
                        <b>Metodo de pago: </b> Billetera<br><br>
                        @elseif($order->payment_method == 3)
                        <b>Metodo de pago: </b>  Tarjeta Crédito/Débito<br><br>
                        @endif
                    </p>
                    <hr>
                    <div class="text-center opacity-75"></div>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ Asset(env('admin').'/order?status=0') }}" type="submit" class="btn btn-success btn-cta">Volver al área de pedidos</a>
</div> 

@endsection