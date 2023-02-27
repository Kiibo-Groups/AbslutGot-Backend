@extends('user.layout.main')

@section('title') Programas de lealtad @endsection

@section('content')
<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 m-b-30">
                    <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                            <a href="{{ Asset($form_url.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-warning">
                                Agregar Nuevo Programa
                            </a>&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
 
                    <div class="card-body table-responsive">
                        <table class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titulo</th>
                                    <th>Visitas</th>
                                    <th>Consumo Minimo</th>
                                    <th>Descripción</th>
                                    <th>Fecha agregado</th>
                                    <th>Status</th>
                                    <th style="text-align: right">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <tr>
                                    <td>
                                        #{{ $row->id }}
                                    </td>
                                    <td>
                                        {{$row->title}}
                                    </td>
                                    <td>
                                        {{$row->visits}}
                                    </td>
                                    <td>
                                        ${{ number_format($row->consum_min,2) }}
                                    </td>
                                    <td width="250px">
                                        {{ $row->descript }}
                                    </td>
                                    <td>
                                        {{ date('d-M-Y',strtotime($row->created_at)) }}
                                    </td>
                                    <td>
                                        @if($row->status == 0)
                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($form_url.'status/'.$row->id) }}')">Activo</button>
                                        @else
                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($form_url.'status/'.$row->id) }}')">Inactivo</button>
                                        @endif
                                    </td>

                                    <td style="text-align: right">
                                        <a href="{{ Asset($form_url.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="mdi mdi-border-color"></i></a>
                                        <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($form_url."delete/".$row->id) }}')"><i class="mdi mdi-delete-forever"></i></button>
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