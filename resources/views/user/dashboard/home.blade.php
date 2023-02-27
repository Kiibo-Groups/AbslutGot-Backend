@extends('user.layout.main')

@section('title') Bienvenido(a)! @endsection

@section('breadcrumb') Dashboard @endsection


@section('content')
 
<div class="col-lg-12">
    <div class="row">
        
        <!-- Saldos -->
        <div class="col-12">
            <div class="card">
                <div class="text-center card-body">
                    <div class="text-success">
                        <div class="avatar avatar-sm ">
                            <i class="bi bi-bar-chart" style="font-size:45px;"></i> 
                        </div> 
                    </div>
                    

                    <div class=" text-center">
                        
                        @if($overview['saldos'] > 0)
                        <!-- Saldo a favor -->
                        <h3 style="font-size: 19px">Tienes un saldo a favor de:</h3>
                        @else 
                        <!-- Saldo que debe -->
                        <h3 style="font-size: 19px">Tienes un saldo deudor de:</h3>
                        @endif
                    </div>
                    <div class="text-overline ">
                        @if($overview['saldos'] > 0)
                        <!-- Saldo a favor -->
                        <h1 style="color:green;">{{$currency}}{{ number_format($overview['saldos'],2) }} <i class="mdi mdi-trending-up"></i></h1>
                        @else 
                        <!-- Saldo que debe -->
                        <?php
                            $sal = str_replace('-','',$overview['saldos']);
                        ?>
                        <h1 style="color:red;">{{$currency}}{{ number_format($sal,2) }} <i class="mdi mdi-trending-down"></i> </h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Saldos -->

        @include('user.dashboard.overview') 
        @include('user.dashboard.chart')
      
    </div>
</div>
   
@endsection