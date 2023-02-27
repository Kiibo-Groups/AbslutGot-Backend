@extends('user.layout.main')

@section('title') Administrar categorías @endsection
@section('breadcrumb') Categorias @endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">
            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">Agregar Categoria</a>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
       

            <div class="card-body">
                 
                {!! $data->links() !!}

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sort Order</th>
                                <th>Name</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th style="text-align: right">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td width="15%">{{ $row->sort_no }}</td>
                                <td width="20%">{{ $row->name }} 
                                    @if($row->id_element != '')
                                    <small>({{$row->id_element}})</small>
                                    @endif
                                </td>
                                <td width="20%">
                                    @if($user->subtype == 0)
                                        @if($row->type == 0)
                                            De Menú
                                        @else
                                            De Complemento
                                        @endif
                                    @else
                                        Producto
                                    @endif
                                </td>
                                <td width="27%">
                                    @if($row->status == 0)
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
                                    @else
                                    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
                                    @endif
                                </td>
                                <td width="19%" style="text-align: right">
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
        
        {!! $data->links() !!}
    </div>
</div>
@endsection