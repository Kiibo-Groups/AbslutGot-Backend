<div class="modal fade modal-slide-right" id="slideRightModalStaff{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="slideRightModalStaff" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="slideRightModalStaff">
                    Solicitud de envio para el pedido #{{ $row->id }} 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- {{ Asset('orderStatus?id='.$row->id.'&status=1.5&staff_ext=2') }} -->
                <form action="{{ Asset('orderStatus') }}" method="get">
                    <input type="hidden" name="id" value="{{ $row->id }}">
                    <input type="hidden" name="status" value="1.5">
                    <input type="hidden" name="staff_ext" value="2">
                    <div class="row">
                        <div class="form-group col-md-12" style="text-align: left">
                            <label for="inputEmail4" >Selecciona el tipo de Repartidor</label>
                            <select name="type_staff" class="form-control" required="">
                            <!-- // 0 = Auto, 1 = Moto, 2 = Bici -->
                                <option value="0">Auto</option>
                                <option value="1">Moto</option>
                                <option value="2">Bicicleta</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cerrar Ventana
                </button>
            </div>
        </div>
    </div>
</div>
