<div class="modal fade modal-slide-right" id="assignModalStaff{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="assignModalStaff" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalStaff">
                    Asignación de pedido #{{ $row->id }} 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {!! Form::open(['url' => [$form_url],'files' => true,'method' => 'POST'],['class' => 'col s12']) !!}

                    <input type="hidden" name="id" value="{{ $row->id }}">
                    <div class="row">
                        <div class="form-group col-md-12" style="text-align: left">
                            <label for="inputEmail4" >Selecciona el Repartidor</label>
                            <select name="d_boy" class="form-control">
                                @foreach($boys as $dboy)
                                    <option value="{{ $dboy->id }}" @if(in_array($dboy->id,$arraydboy)) selected @endif>
                                        {{ $dboy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Asignar Repartidor</button>
                </form>
            </div>
        </div>
    </div>
</div>
