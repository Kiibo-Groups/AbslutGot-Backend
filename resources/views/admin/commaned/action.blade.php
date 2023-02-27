
@if($row->status == 0)

    <div class="btn-group" role="group">
        <button id="btnGroupDrop{{ $row->id }}" type="button" 
                class="btn btn-secondary dropdown-toggle" 
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opciones 
        </button>

        <div class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $row->id }}" style="padding: 10px 10px">
            <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                Ver solicitud    
            </a><hr>
            <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=0&staff_ext=1') }}" onclick="return confirm('Estas Seguro(a)?')">Solicitar Repartidor</a><hr>
            <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Estas Seguro(a)?')">Cancelar Servicio</a><hr>
        </div>
    </div>

@elseif($row->status == 2)

    <span style="font-size: 12px">Cancelado a las <br>{{ $row->updated_at }}</span>

@elseif($row->status == 6)
    <div class="btn-group" role="group">
        <button id="btnGroupDrop{{ $row->id }}" type="button" 
                class="btn btn-secondary dropdown-toggle" 
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opciones 
        </button>

        <div class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $row->id }}" style="padding: 10px 10px">
            <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                Ver solicitud    
            </a><hr>
            <a href="{{ Asset('/upload/order/delivery/'.$row->pic_end_order) }}" target="_blank">Ver imagen de entrega</a><hr>
        </div>
    </div>
@else

    <div class="btn-group" role="group">
        <button id="btnGroupDrop{{ $row->id }}" type="button" 
                class="btn btn-secondary dropdown-toggle" 
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opciones 
        </button>

        <div class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $row->id }}" style="padding: 10px 10px">
            <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                Ver solicitud    
            </a><hr>
            <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=0&staff_ext=1') }}" onclick="return confirm('Estas Seguro(a)?')">Solicitar Repartidor</a><hr>
            <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Estas Seguro(a)?')">Cancelar Servicio</a><hr>
        </div>
    </div>

@endif