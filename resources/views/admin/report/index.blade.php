@extends('admin.layout.main')

@section('title') Reportes de venta @endsection
@section('breadcrumb') Descarga un report @endsection

@section('content')

<div class="col-lg-10 mx-auto">
    <div class="row">
        <div class="card py-3 m-b-30">
            <div class="card-body">
                {!! Form::open(['url' => [$form_url],'target' => '_blank'],['class' => 'col s12']) !!}

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="inputEmail4">Select Store</label>
                            <select name="store_id" class="form-select">
                                <option value="">All Store</option>
                                @foreach($data as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="inputEmail4">From Date</label>
                            {!! Form::text('from',null,['class' => 'js-datepicker form-control','required' => 'required'])!!}
                        </div>

                        <div class="col-md-6">
                            <label for="inputEmail4">To Date</label>
                            {!! Form::text('to',null,['class' => 'js-datepicker form-control','required' => 'required'])!!}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="inputEmail4">Tipo de reporte</label>
                            <select name="type_report" class="form-select">
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
@endsection