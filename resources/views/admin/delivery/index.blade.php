@extends('admin.layout.main')

@section('title') Administrar personal de entrega @endsection

@section('icon') mdi-account-clock @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 m-b-30">

                    <div class="row">
                        <div class="col-md-12" style="text-align: right;"><a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-warning">Add New</a>&nbsp;&nbsp;&nbsp;</div>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover ">
                            <thead>
                            <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Ciudad</th>
                            <th>Saldos</th>
                            <th>Estatus</th>
                            <th>Bloqueo</th>
                            <th style="text-align: right">Opciones</th>
                            </tr>

                            </thead>
                            <tbody>

                            @foreach($data as $row)

                            @if($row->store_id == 0)
                            <tr>
                            <td width="1%">#{{ $row->id }}</td>
                            <td width="20%">{{ $row->name }}</td>
                            <td width="20%">
                                <!--  0 = Auto, 1 = Moto, 2 = Bici -->
                                @if($row->type_driver == 0)
                                    <img src="{{ Asset('assets/img/type_car.png') }}" alt="Auto" style="width:80px;">
                                @elseif($row->type_driver == 1)
                                    <img src="{{ Asset('assets/img/type_moto.png') }}" alt="Auto" style="width:80px;">
                                @elseif($row->type_driver == 2)
                                    <img src="{{ Asset('assets/img/type_bici.png') }}" alt="Auto" style="width:80px;">
                                @endif
                            </td>
                            <td width="20%">
                                {{$row->city}}
                            </td>
                            <td width="15%">
                                @if($row->amount_acum == 0)
                                    <!-- Saldo a favor -->
                                    <h6 style="color:blue;">{{$currency}}{{ number_format($row->amount_acum,2) }}</h6>
                                @elseif($row->amount_acum > 0)
                                    <!-- Saldo a favor -->
                                    <h6 style="color:red;" data-toggle="tooltip" data-placement="top" data-original-title="Tiene un saldo a favor de:">{{$currency}}{{ number_format($row->amount_acum,2) }} <i class="mdi mdi-trending-down"></i></h6>
                                @else 
                                    <!-- Saldo que debe -->
                                    <?php
                                        $sal = str_replace('-','',$row->amount_acum);
                                    ?>
                                    <h6 style="color:green;" data-toggle="tooltip" data-placement="top" data-original-title="Tiene un saldo deudor de:">{{$currency}}{{ number_format($sal,2) }} <i class="mdi mdi-trending-up"></i> </h6>
                                @endif
                            </td>
                            <td width="5%">
                                @if($row->status == 0)
                                    <!-- <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button> -->
                                    <a href="#" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')" data-toggle="tooltip" data-placement="top" data-original-title="Activar"><i class="mdi mdi-disc"></i></a>
                                @else
                                    <!-- <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button> -->
                                    <a href="#" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')" data-toggle="tooltip" data-placement="top" data-original-title="Desactivar"><i class="mdi mdi-disc"></i></a>
                                @endif
                            </td>
                            <td width="5%">
                                @if($row->status_admin == 0)
                                    <!-- <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button> -->
                                    <a href="#" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" onclick="confirmAlert('{{ Asset($link.'status_admin/'.$row->id) }}')" data-toggle="tooltip" data-placement="top" data-original-title="Activar"><i class="mdi mdi-disc"></i></a>
                                @else
                                    <!-- <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button> -->
                                    <a href="#" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" onclick="confirmAlert('{{ Asset($link.'status_admin/'.$row->id) }}')" data-toggle="tooltip" data-placement="top" data-original-title="Activar"><i class="mdi mdi-disc"></i></a>
                                @endif
                            </td>
                            
                            <td width="20%" style="text-align: right">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Opciones
                                    </button>
                                    
                                    <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                      
                                        <!-- Reset Saldos -->
                                        <li>
                                            <a href="{{ Asset($link.'pay/'.$row->id) }}" class="dropdown-item">
                                                Agregar Pago
                                            </a>
                                        </li>
                                        <!-- ViewInfo -->
                                        <li>
                                            <a href="javascript::void()" class="dropdown-item" onclick="showMsg('Username : {{ $row->phone }}<br>Password : {{ $row->shw_password }}')">
                                                Ver Accesos
                                            </a>
                                        </li>
                                        <!-- Edit -->
                                        <li>
                                            <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                                                Editar
                                            </a>
                                        </li>
                                        <!-- Reportes -->
                                        <li>
                                            {!! Form::open(['url' => [$form_url],'target' => '_blank']) !!}
                                                <input type="hidden" name="staff_id" value="{{$row->id}}">
                                                <input type="hidden" name="type_report" value="excel">
                                                
                                                <button type="submit" class="dropdown-item">
                                                    Descarga de reportes    
                                                </button>
                                            </form>
                                            
                                        </li>
                                        <!-- Delete -->
                                        <li>
                                            <a href="javascript::void()" class="dropdown-item" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')">
                                                Eliminar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            </tr>
                            @endif

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

