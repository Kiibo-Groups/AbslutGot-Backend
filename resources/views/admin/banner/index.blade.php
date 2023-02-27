@extends('admin.layout.main')

@section('title') Banners @endsection
@section('breadcrumb') Listado @endsection

@section('content')

<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">

            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
                    Agregar Banner</a>&nbsp;&nbsp;&nbsp;
                </div>
            </div>

            <div class="card-body  py-3 m-b-30" style="padding-top: 25px">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Ciudad</th>
                                <th>Imagen</th>
                                <th>Posición</th>
                                <th>Status</th>
                                <th style="text-align: right">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td width="17%">@if($row->city) {{ $row->city }} @else All City @endif</td>
                                <td width="17%"><img src="{{ Asset('upload/banner/'.$row->img) }}" height="70"></td>
                                <td width="17%">{{ $row->getPosition($row->position) }}</td>
                                <td width="17%">
                                    @if($row->status == 0)
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
                                    @else
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
                                    @endif
                                </td>
                                <td width="17%" style="text-align: right">
                                    <a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="ri-edit-2-fill"></i></a>
                                    <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="ri-delete-bin-2-fill"></i></button>
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
@endsection