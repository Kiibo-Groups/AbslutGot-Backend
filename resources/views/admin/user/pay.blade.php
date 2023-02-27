@extends('admin.layout.main')

@section('title') Agregar Pago @endsection

@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto  mt-2">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                            <div class="form-row">
                                <input type="text" name="deliveryVia" value="admin" hidden>
                                <div class="form-group col-md-6">
                                    @if($data->saldo == 0)
                                        <label for="inputEmail6">Esta al corriente</label>
                                        <br />
                                        <!-- Saldo a favor -->
                                        <h3 style="color:blue;">{{$currency}}{{ number_format($data->saldo,2) }}</h3>
                                    @elseif($data->saldo > 0)
                                        <label for="inputEmail6">Tiene un saldo a favor de</label>
                                        <br />
                                        <!-- Saldo a favor -->
                                        <h3 style="color:red;">{{$currency}}{{ number_format($data->saldo,2) }} </h3>
                                    @else 
                                        <label for="inputEmail6">Tiene un saldo deudor de</label>
                                        <br />
                                        <!-- Saldo que debe -->
                                        <?php
                                            $sal = str_replace('-','',$data->saldo);
                                        ?>
                                        <h3 style="color:green;">{{$currency}}{{ number_format($sal,2) }} </h3>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="new_saldo">Depósito</label>
                                    <input type="number" id="new_saldo" name="new_saldo" placeholder="Ingresa el monto del deposito" step="0.01" class="form-control">
                                </div>
                            </div>
                            <a href="javascript::void()" class="btn btn-success" onclick="confirmAlert('{{ Asset($link.'payAll/'.$data->id) }}')">Restablecer Saldo</a>
                            <button type="submit" style="float:right;text-align:right;" class="btn btn-success">Agregar Deposito</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
