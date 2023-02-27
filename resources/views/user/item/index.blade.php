@extends('user.layout.main')

@section('title') Administrar artículos @endsection
@section('breadcrumb') Catalogo @endsection


@section('content')

<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">

            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
                    Agregar Nuevo
                    </a>&nbsp;&nbsp;&nbsp;
                    {{-- <a href="{{ Asset('import') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
                        Importar
                    </a> --}}
                </div>
            </div>


            <div class="card-body">
                <div class="table-responsive"></div>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Categoría</th>
                                <th>Nombre</th>
                                <th>Ordenamiento</th>
                                <th>Estado</th>
                                <th style="text-align: right">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td width="15%">@if($row->img) <img src="{{ Asset('upload/item/'.$row->img) }}" height="50"> @endif</td>
                                <td width="12%">{{ $row->cate }}</td>
                                <td width="17%">{{ $row->name }}</td>
                                <td width="12%">{{ $row->sort_no }}</td>
                                <td width="12%">
                                    @if($row->status == 0)
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
                                    @else
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
                                    @endif
                                </td>
                                <td width="22%" style="text-align: right">
                                    @if(Auth::user()->subtype == 0)
                                    <!-- Complementos -->
                                    <a href="javascript::void()" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-info" data-bs-toggle="modal" data-bs-target="#slideRightModal{{ $row->id }}" data-bs-title="Agregale complementos"><i class="ri-link"></i></a>
                                    @endif
                                    <!-- Tranding -->
                                    <a href="javascript::void()" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle <?php if($row->trending == 1){ echo "btn-success"; } else { echo "btn-warning"; } ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php if($row->trending == 1){ echo "En Trending"; } else { echo "Marcar Trending"; } ?>" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id.'?type=trend') }}')"><i class="ri-contacts-book-upload-line"></i></a>
                                    <!-- Editar -->
                                    <a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit This Entry"><i class="ri-edit-2-fill"></i></a>
                                    <!-- Eliminar -->
                                    <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="ri-delete-bin-2-fill"></i></button>
                                </td>
                            </tr>
                            @include('user.item.addon')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection