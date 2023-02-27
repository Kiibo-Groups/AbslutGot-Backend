<div class="modal fade modal-slide-right" id="slideRightModal{{ $row->id }}" tabindex="-1" role="dialog aria-labelledby="slideRightModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="slideRightModalLabel">Listo para despachar #{{ $row->id }}</h5>
                <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <form action="{{ Asset($form_url) }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{ $row->id }}">
                    <div class="row">
                        <div class="form-group col-md-12" style="text-align: left">
                            <label for="inputEmail4" >Selecciona el Repartidor</label>
                            <select name="d_boy" class="form-control" required="">
                            <option value="">Select</option>
                            @foreach($boys as $b)
                                @if($b->status_send == 0)
                                    <option value="{{ $b->id }}">
                                        {{ $b->name }}
                                    </option>
                                @elseif($b->status_send == 1)
                                    <option value="{{ $b->id }}">
                                        {{ $b->name }}
                                        (En Ruta)
                                    </option>
                                @endif
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Despachar pedido</button>
                </form> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                Cerrar
                </button>
            </div>
        </div>
    </div>
</div>