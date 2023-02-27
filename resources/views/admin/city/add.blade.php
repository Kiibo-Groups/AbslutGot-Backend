@extends('admin.layout.main')

@section('title') Nueva ciudad @endsection
@section('breadcrumb') Agregar elemento @endsection


@section('content')
<div class="col-lg-12">
    <div class="row">
        {!! Form::model($data, ['url' => [$form_url],'files' => true]) !!}
            @include('admin.city.form') 
        </form>
    </div>
</div>
@endsection