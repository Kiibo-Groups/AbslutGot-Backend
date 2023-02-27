@extends('admin.layout.main')

@section('title') Bienvenido(a)! Administrador @endsection
@section('breadcrumb') Dashboard @endsection
 

@section('content')
 
<div class="col-lg-12">
    <div class="row">
        
        @include('admin.dashboard.overview')
        @include('admin.dashboard.chart') 
      
    </div>
</div>
   
@endsection