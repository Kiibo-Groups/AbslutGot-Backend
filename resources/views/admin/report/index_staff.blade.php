@extends('admin.layout.main')

@section('title') Reporte de Repartidores @endsection

@section('icon') mdi-send @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-lg-10 mx-auto  mt-2">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        {!! Form::open(['url' => [$form_url],'target' => '_blank'],['class' => 'col s12']) !!}

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Selecciones el Socio Repartidor</label>
                                    <select name="staff_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach($data as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Tipo de reporte</label>
                                    <select name="type_report" class="form-control">
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-cta">Imprimir Reporte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection