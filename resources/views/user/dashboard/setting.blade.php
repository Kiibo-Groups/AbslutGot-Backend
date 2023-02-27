@extends('user.layout.main')

@section('title') Actualiza tu información @endsection
@section('breadcrumb') Configuraciones @endsection

@section('content')
<div class="col-lg-10 mx-auto">
    <div class="row"> 
        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'POST']) !!}
            @include('admin.user.form',['type' => 'user'])
        </form> 
    </div>
</div>

@endsection