
<div class="form-row">
	<div class="form-group col-md-6">
		<label for="mesa"># de mesa</label>
		<input type="number" id="mesa" name="mesa" value="{{ $data->mesa }}" class="form-control">
	</div>

	<div class="form-group col-md-6">
		<label for="inputEmail6">Estado</label>
		<select name="status" class="form-control">
			<option value="0" @if($data->status == 0) selected @endif>Disponible</option>
			<option value="1" @if($data->status == 1) selected @endif>No Disponible</option>
		</select>
	</div>	
</div>

<div class="form-row">
	<div class="form-group col-md-12">
		<label for="descript">Descripción <small>(Solo tú podras visualizarla)</small> </label>
		<input type="text" id="descript" name="descript" value="{{$data->descript}}" class="form-control">
	</div>
</div>


<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
