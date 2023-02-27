@extends('admin.layout.main')

@section('title') Editar Banner @endsection
@section('breadcrumb') Editar elemento @endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        <div class="card py-3 m-b-30">
            <div class="card-body">
                {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                @include('admin.banner.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection