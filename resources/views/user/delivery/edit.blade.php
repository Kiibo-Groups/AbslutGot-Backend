@extends('user.layout.main')

@section('title') Editar Conductor @endsection

@section('icon') mdi-calendar @endsection


@section('content')
<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto"> 
                {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                    @include('user.delivery.form')
                </form> 
            </div>
        </div>
    </div>
</section>

@endsection