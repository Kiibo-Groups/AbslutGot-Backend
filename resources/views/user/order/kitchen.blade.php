@extends('user.layout.main')

@section('title') Pedidos en cocina @endsection
@section('breadcrumb') Gestor de pedidos @endsection


@section('content')
<section class="section">
    <div class="row align-items-top">
      <!-- Pedidos nuevos -->
      <div class="col-lg-4">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#new_orders" aria-expanded="true" aria-controls="new_orders">
                    Pedidos Nuevos
                    @if($new_order > 0)
                    &nbsp;&nbsp;&nbsp;<span class="badge bg-info text-white">{{$new_order}}</span>
                    @endif
                </button>
              </h2>
              <div id="new_orders" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                @foreach($data as $row)
                    @if($row['st'] == 1)
                    <div class="accordion-body">
                        <div class="card">  
                            <div class="card-body">
                                <h5 class="card-title">{{$row['date']}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Pedido #{{ $row['id'] }}</h6>
                                <p class="card-text">
                                @foreach($item->getItem($row['id']) as $i)
                                    <div class="row" style="font-size: 12px">
                                        <div class="col-md-6">{{$i['item'] }}</div>
                                        <div class="col-md-3">x{{ $i['qty'] }}</div>
                                        <div class="col-md-3">{{ $currency.$i['price'] }}</div>
                                    </div><hr>
                    
                                    @if(count($item->getAddon($i['cart_no'],$row['id'])) > 0)
                                        @foreach($item->getAddon($i['cart_no'],$row['id']) as $add)
                                            <div class="row" style="font-size: 12px">
                                                <div class="col-md-6">{{ $add->addon }}</div>
                                                <div class="col-md-3">x{{ $add->qty }}</div>
                                                <div class="col-md-3">{{ $currency.$add->price }}</div>
                                            </div><hr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </p>
                                <h6 class="card-subtitle mb-2 text-muted text-blue">Notas Extra: {{ $row['notes'] }}</h6>
        
                                <p class="card-text">
                                <a href="#">
                                    <img src="data:image/png;base64,{{ $row['code_order'] }}" class="card-img-bottom py-3 m-t-30" alt="qr" style="width: 20%;margin: 5px auto;">
                                </a>
                                </p>
                                
                                <a href="{{ Asset('/orderStatus?id='.$row['id'].'&status=1.5') }}" class="card-link btn btn-primary btn-lg">Comenzar a preparar</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
              </div>
            </div> 
        </div> 
      </div>
      <!-- Pedidos nuevos -->
      
      <!-- Pedidos en proceso -->
      <div class="col-lg-4">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#order_procs" aria-expanded="true" aria-controls="order_procs">
                    Pedidos en preparación
                    @if($order_procs > 0)
                    &nbsp;&nbsp;&nbsp;<span class="badge bg-warning text-white">{{$order_procs}}</span>
                    @endif
                </button>
              </h2>
              <div id="order_procs" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                @foreach($data as $row)
                    @if($row['st'] == 1.5)
                    <div class="accordion-body">
                        <div class="card">  
                            <div class="card-body">
                                <h5 class="card-title">{{$row['date']}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Pedido #{{ $row['id'] }}</h6>
                                <p class="card-text">
                                @foreach($item->getItem($row['id']) as $i)
                                    <div class="row" style="font-size: 12px">
                                        <div class="col-md-6">{{$i['item'] }}</div>
                                        <div class="col-md-3">x{{ $i['qty'] }}</div>
                                        <div class="col-md-3">{{ $currency.$i['price'] }}</div>
                                    </div><hr>
                    
                                    @if(count($item->getAddon($i['cart_no'],$row['id'])) > 0)
                                        @foreach($item->getAddon($i['cart_no'],$row['id']) as $add)
                                            <div class="row" style="font-size: 12px">
                                                <div class="col-md-6">{{ $add->addon }}</div>
                                                <div class="col-md-3">x{{ $add->qty }}</div>
                                                <div class="col-md-3">{{ $currency.$add->price }}</div>
                                            </div><hr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </p>
                                <h6 class="card-subtitle mb-2 text-muted text-blue">Notas Extra: {{ $row['notes'] }}</h6>
        
                                <p class="card-text">
                                <a href="#">
                                    <img src="data:image/png;base64,{{ $row['code_order'] }}" class="card-img-bottom py-3 m-t-30" alt="qr" style="width: 20%;margin: 5px auto;">
                                </a>
                                </p>
                                
                                <a href="{{ Asset('/orderStatus?id='.$row['id'].'&status=3') }}" class="card-link btn btn-primary btn-lg">Listo para entregar</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
              </div>
            </div> 
        </div> 
      </div>
      <!-- Pedidos en proceso -->

      <!-- Pedidos Listos -->
      <div class="col-lg-4">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#order_ready" aria-expanded="true" aria-controls="order_ready">
                    Pedidos Listos
                    @if($order_ready > 0)
                    &nbsp;&nbsp;&nbsp;<span class="badge bg-success text-white">{{$order_ready}}</span>
                    @endif
                </button>
              </h2>
              <div id="order_ready" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                @foreach($data as $row)
                    @if($row['st'] == 3)
                    <div class="accordion-body">
                        <div class="card">  
                            <div class="card-body">
                                <h5 class="card-title">{{$row['date']}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Pedido #{{ $row['id'] }}</h6>
                                <p class="card-text">
                                @foreach($item->getItem($row['id']) as $i)
                                    <div class="row" style="font-size: 12px">
                                        <div class="col-md-6">{{$i['item'] }}</div>
                                        <div class="col-md-3">x{{ $i['qty'] }}</div>
                                        <div class="col-md-3">{{ $currency.$i['price'] }}</div>
                                    </div><hr>
                    
                                    @if(count($item->getAddon($i['cart_no'],$row['id'])) > 0)
                                        @foreach($item->getAddon($i['cart_no'],$row['id']) as $add)
                                            <div class="row" style="font-size: 12px">
                                                <div class="col-md-6">{{ $add->addon }}</div>
                                                <div class="col-md-3">x{{ $add->qty }}</div>
                                                <div class="col-md-3">{{ $currency.$add->price }}</div>
                                            </div><hr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </p>
                                <h6 class="card-subtitle mb-2 text-muted text-blue">Notas Extra: {{ $row['notes'] }}</h6>
        
                                <p class="card-text">
                                <a href="#">
                                    <img src="data:image/png;base64,{{ $row['code_order'] }}" class="card-img-bottom py-3 m-t-30" alt="qr" style="width: 20%;margin: 5px auto;">
                                </a>
                                </p>
                                
                                <a href="{{ Asset('/orderStatus?id='.$row['id'].'&status=5') }}" class="card-link btn btn-primary btn-lg">Entregar Pedido</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
              </div>
            </div> 
        </div> 
      </div>
       <!-- Pedidos Listos -->
    </div>
  </section>
@endsection