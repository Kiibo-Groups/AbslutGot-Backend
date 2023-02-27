@extends('user.layout.main')

@section('title') Administrar complementos @endsection
@section('breadcrumb') Complementos @endsection


@section('content')

<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">

            <div class="row">
                <div class="col-md-12" style="text-align: right;"><a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">Agregar Nuevo</a>&nbsp;&nbsp;&nbsp;</div>
            </div>

            <div class="card-body py-3 m-b-30">
            
                {!! $data->links() !!}

                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th style="text-align: right">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td width="25%">{{ $row->cate }}
                                @if($row->id_element != '')
                                    <small>({{$row->id_element}})</small>
                                @endif
                            </td>
                            <td width="25%">{{ $row->name }}</td>
                            <td width="25%">{{ $c.$row->price }}</td>
                            <td width="25%" style="text-align: right">
                                <a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="ri-edit-2-fill"></i></a>
                                <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="ri-delete-bin-2-fill"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        {!! $data->links() !!}
    </div>
</div>
@endsection