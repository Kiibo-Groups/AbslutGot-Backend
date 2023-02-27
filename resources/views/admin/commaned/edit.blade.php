@extends('admin.layout.main')

@section('title') Editar Servicio @endsection

@section('icon') mdi-comment-plus @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 mx-auto">
                {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                        
                    <div class="card">
                        <div class="card-body">
                                @csrf
                                @include('admin.commaned.form')
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                                @csrf
                                @include('admin.commaned.google')
                        </div>
                    </div>
                
                    <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                </form>
                
            </div>
        </div>
    </div>
</section>

@endsection