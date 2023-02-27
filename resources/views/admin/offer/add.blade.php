@extends('admin.layout.main')

@section('title') Agregar Oferta @endsection
@section('breadcrumb') Agregar elemento @endsection
  
@section('content')
<div class="col-lg-12">
    <div class="row">
        {!! Form::model($data, ['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
            @include('admin.offer.form')
        </form>
    </div>
</div> 
@endsection