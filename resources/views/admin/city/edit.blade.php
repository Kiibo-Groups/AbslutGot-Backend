@extends('admin.layout.main')

@section('title') Detalles de la ciudad @endsection
@section('breadcrumb') Editar elemento @endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH']) !!}
            @include('admin.city.form') 
        </form>
    </div>
</div>
@endsection