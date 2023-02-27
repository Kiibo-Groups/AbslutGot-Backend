@extends('admin.layout.main')

@section('title') Editar Comercio @endsection
@section('breadcrumb') Editar elemento @endsection


@section('content')
<div class="col-lg-11 mx-auto">
    <div class="row">
        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
        @include('admin.user.form')
        </form>
    </div>
</div>
@endsection