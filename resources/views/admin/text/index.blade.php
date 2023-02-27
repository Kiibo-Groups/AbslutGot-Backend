@extends('admin.layout.main')

@section('title') Textos de la aplicación @endsection
 
@section('breadcrumb') Textos @endsection



@section('content')

<div class="col-lg-12">
    <div class="row"> 
        {!! Form::model($data, ['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
            @include('admin.text.form')
        </form> 
    </div>
</div>
@endsection