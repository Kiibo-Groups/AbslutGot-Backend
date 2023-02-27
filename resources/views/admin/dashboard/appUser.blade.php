@extends('admin.layout.main')

@section('title') Usuarios Registrados @endsection

@section('icon') mdi-home @endsection


@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-md-12">
<div class="card py-3 m-b-30">

<div class="card-body">
<table class="table table-hover ">
<thead>
<tr>
<th>Usuario</th>
<th>Email</th>
<th>Telefono</th>
<th>Fecha de registro</th>
<th>Pedidos</th>
<th>Estado</th>
<th>Eliminar</th>
</tr>

</thead>
<tbody>

@foreach($data as $row)

<tr>
<td width="15%">{{ $row->name }}</td>
<td width="20%">{{ $row->email }}</td>
<td width="10%">{{ $row->phone }}</td>
<td width="15%">{{ date('d-M-Y',strtotime($row->created_at)) }}</td>
<td width="10%">{{ $row->countOrder($row->id) }}</td>
<td width="10%">
    @if($row->status == 0)
    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Activo</button>
    @else
    <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Bloqueado</button>
    @endif
</td>
<td width="10%">
<button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 " onclick="confirmAlert('{{ Asset($link.'trash/'.$row->id) }}')">
<i class="mdi mdi-delete" style="font-size:22px;"></i>
</button>
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
</div>
</section>

@endsection