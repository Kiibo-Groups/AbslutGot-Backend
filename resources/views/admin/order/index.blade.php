@extends('admin.layout.main')

@section('title') {{ $title }} @endsection
@section('breadcrumb') {{ $title }} @endsection

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
                                <th>Negocio</th>
                                <th>Usuario</th>
                                <th>Estado del pedido</th>
                                <th>Elementos</th>
                                <th style="text-align: right">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            @include('admin.order.viewElements')
                            <tr>
 
                                <td width="5%">
                                    <a href="https://bincar.kiibo.mx/track_order/?id={{ $row->external_id }}" target="_blank">
                                        <img src="data:image/png;base64,{{ $row->code_order }}" style="width:50px;height: 50px;max-width:none !important;">
                                    </a> 
                                </td>
                                <td width="15%">{{ $row->store }}</td>
                                <td width="15%">
                                    {{ $row->name }}<br>
                                    <small>Tel: {{ $row->phone }}</small>    
                                </td>
                                <td width="20%">	
                                    @if($row->InnStore == 1)
                                        <span>Pedido en mesa <b>#{{$row->mesa}}</b></span><br>
                                    @endif 
                                
                                    @if($row->status == 0 )
                                    <span class="badge bg-success text-white">Pedido Nuevo</span>
                                    @elseif($row->status == 1)
                                    <span class="badge bg-primary text-white">Pedido Aceptado</span>
                                    @elseif($row->status == 2)
                                    <span style="font-size: 12px">Cancelado a las <br>{{ $row->status_time }}</span>
                                    @elseif($row->status == 1.5)
                                    <span class="badge bg-secondary text-white">Pedido en preparación</span>
                                    @elseif($row->status == 3)
                                        @if($row->type == 1) <!-- A domicilio -->
                                            <span class="badge bg-info text-white">Repartidor en camino</span>
                                        @else
                                            <span class="badge bg-info text-white">Listo para entregar</span>
                                        @endif
                                    @elseif($row->status == 4)
                                    <span class="badge bg-success text-white">Pedido Entregado</span> 
                                    @elseif($row->status == 5 || $row->status == 6)
                                        <span class="badge bg-success text-white">Pedido Entregado</span> 
                                    @endif  
                                </td>
                                <td width="20%">	
                                    <button href="javascript::void()" data-bs-toggle="modal" data-bs-target="#viewModalElements{{ $row->id }}" class="btn btn-primary">
                                        Vista de elementos
                                    </button>
                                </td>

                                <td width="23%">
                                    @include('admin.order.action')
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
        {!! $data->appends(request()->except('page'))->links() !!}
    </div>
</div>
@endsection