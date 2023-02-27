@extends('admin.layout.main')

@section('title') {{ $title }} @endsection

@section('content')
<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 m-b-30">
                    <div class="row">
                        <div class="col-md-12" style="text-align: right;"><a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-warning">Agregar Servicio</a>&nbsp;&nbsp;&nbsp;</div>
                    </div>
                    
                    <div class="card-body">
                        <table class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                    <th>Repartidor</th>
                                    <th>Envio</th>
                                    <th>IVA</th>
                                    <th>Total</th>
                                    <th>Metodo de pago</th>
                                    <th style="text-align: right">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <tr>
                                    <td width="3%">#{{ $row->id }}</td>
                                    <td width="12%">{{ $row->name_user }}</td>
                                    <td width="10%">
                                        <a href="http://maps.google.com/?q={{ $row->address_origin }}" target="_blank">
                                            {{ substr($row->address_origin,0,25) }} ...
                                        </a>
                                    </td>
                                    <td width="10%">
                                        <a href="http://maps.google.com/?q={{ $row->address_destin }}" target="_blank">
                                            {{ substr($row->address_destin,0,25) }} ...
                                        </a>
                                    </td>
                                    <td width="9%">
                                        {{ $comm_f->viewDboyComm($row->id) }}
                                    </td>
                                    <td width="10%">
                                        {{ number_format(round($row->d_charges,0),2,".",",") }}
                                    </td>
                                    <td width="10%">
                                        {{ number_format(round($row->iva_charges,0),2,".",",") }}
                                    </td>
                                    <td width="10%">
                                        {{ number_format(round($row->total,0),2,".",",") }}
                                    </td>
                                    <td width="18%">
                                        @if($row->payment_method == 0)
                                            <span style="color:green;">Pago en Efectivo</span>
                                        @else 
                                            <span style="color:green;">Medios Electronicos</span>
                                        @endif
                                    </td>
                                    <td width="8%" style="text-align: right">
                                        @if($row->status == 1)
                                         <span style="color:green;">Pedido Aceptado</span>
                                         @elseif($row->status == 4.5)
                                         <span style="color:green;">Pedido en ruta de entrega</span>
                                         @endif
                                        @include('admin.commaned.action')
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection