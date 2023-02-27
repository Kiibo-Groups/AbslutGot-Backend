@extends('user.layout.main')

@section('title') Administrar Mesas para el comensal @endsection

@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-md-12">
<div class="card py-3 m-b-30">

<div class="row">
    <div class="col-md-12" style="text-align: right;">
        <a  href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
        Agregar Mesa
        </a>&nbsp;&nbsp;&nbsp;
    </div>
</div>


<div class="card-body">
<table class="table table-hover ">
    <thead>
        <tr>
            <th>Mesa</th>
            <th>Link</th>
            <th>Código QR</th>
            <th>Status</th>
            <th style="text-align: right">Opciones</th>
        </tr>
    </thead>
    <tbody>

    @foreach($data as $row)

        <tr>
            <td width="17%">{{ $row->mesa }}</td>
            <td width="17%">
                <a href="{{$row->link}}" target="_blank">Ver Link</a>
            </td>
            <td width="17%">
                <a download="qr_code_{{$row->mesa}}" href="data:image/png;base64,{{ $row->qr }}" target="_blank">
                    <img src="data:image/png;base64,{{ $row->qr }}" alt="Mesa #{{$row->mesa}}" style="max-width:25%;">
                </a> 
            </td>
            <td width="17%">

            @if($row->status == 0)
                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disponible</button>
            @else
                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">No Disponible</button>
            @endif

            </td>

            <td width="15%" style="text-align: right">
                <a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="mdi mdi-border-color"></i></a>
                <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="mdi mdi-delete-forever"></i></button>
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