@extends('admin.layout.main')

@section('title') Código QR @endsection

@section('icon') mdi-chart-line @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="card-body">
                    <div style="background:#fff;" class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                        <div class="col p-4 d-flex flex-column position-static">
                            <h3 class="mb-0">{{$data->name}}</h3>
                            <div class="mb-1 text-muted">Tel: {{$data->phone}}</div>
                            <p class="card-text mb-auto">
                                Email<br />
                                {{$data->email}}
                                <br />
                                web <br />
                                web: <a href="https://{{$data->dominio}}{{$data->ext_dominio}}" target="_blank">{{$data->dominio}}{{$data->ext_dominio}}</a>
                            </p>
                            
                        </div>

                        <div class="col p-4 text-right">
                            <a download="qr_code_{{$data->name}}" href="data:image/png;base64,{{ $data->qr_code }}" target="_blank">
                                <img src="data:image/png;base64,{{ $data->qr_code }}">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

