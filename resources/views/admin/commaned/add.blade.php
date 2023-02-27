@extends('admin.layout.main')

@section('title') Agregar Servicio @endsection

@section('icon') mdi-comment-plus @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                {!! Form::model($data, ['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            @include('admin.commaned.google')

                            @include('admin.commaned.form')
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</section>

@endsection