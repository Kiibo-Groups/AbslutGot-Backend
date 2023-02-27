@extends('admin.layout.main')

@section('title') Push Notifications @endsection

@section('icon') mdi-send @endsection

@section('content')
<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto  mt-2">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        {!! Form::open(['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Titulo</label>
                                    {!! Form::text('title',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="destin_notify">Destino de notificaciones</label>
                                    <select name="destin_notify" class="form-control" id="destin_notify">
                                        <option value="all">Todas</option>
                                        <option value="0">Usuarios</option>
                                        <option value="1">Negocios</option>
                                        <option value="2">Repartidores</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputEmail6">Selección de Ciudades</label>
                                    <select name="citys[]" class="form-control js-select2" multiple="true">
                                        <option value="all">Todas</option>
                                        @foreach($citys as $p)
                                        <option value="{{ $p->id }}" @if(in_array($p->name,$array)) selected @endif>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Imagen (Recomended size 800 x 600)</label>
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Descripción (menos de 250 palabras)</label>
                                    {!! Form::textarea('desc',null,['id' => 'code','class' => 'form-control','required' => 'required','maxlength' => '250'])!!}
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-cta">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection