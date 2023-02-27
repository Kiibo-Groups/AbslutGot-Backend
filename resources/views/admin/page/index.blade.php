@extends('admin.layout.main')

@section('title') Acerca de @endsection
@section('breadcrumb') Acerca de la empresa @endsection

@section('content')

<div class="col-lg-12">
    <div class="row"> 
        {!! Form::model($data, ['url' => [$form_url],'files' => true]) !!}
            @include('admin.page.form')
        </form>
    </div>
</div>
@endsection
 