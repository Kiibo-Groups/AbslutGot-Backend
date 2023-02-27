@extends('admin.layout.main')

@section('title') Editar oferta @endsection
@section('breadcrumb') Editar elemento @endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
        @include('admin.offer.form')
        </form>
    </div>
</div> 
@endsection