<div class="card py-3 m-b-30">
	<div class="card-body">
		<div class="form-row">
			<input type="text" name="deliveryVia" value="user" hidden>
			<input type="hidden" name="c_type_staff" value="0">
			<input type="hidden" name="c_value_staff" value="0"> 
			<div class="form-group col-md-12">
				<label for="inputEmail6">Nombre</label>
				{!! Form::text('name',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputEmail6">Telefono (This will be username)</label>
				{!! Form::text('phone',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
			</div>
			<div class="form-group col-md-6">
				<label for="rfc">RFC</label>
				<input type="text" id="rfc" name="rfc" value="{{$data->rfc}}" required class="form-control">
			</div>

		</div>

		<div class="form-row">
			<div class="form-group col-md-6">
			<label for="inputEmail6">Estado</label>
			<select name="status" class="form-control">
				<option value="0" @if($data->status == 0) selected @endif>Active</option>
				<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
			</select>
			</div>
			
			<div class="form-group col-md-6">
				@if($data->id)
					<label for="inputEmail6">Cambiar Contraeña</label>
					<input type="password" name="password" class="form-control">
				@else
					<label for="inputEmail6">Contraseña</label>
					<input type="password" name="password" class="form-control" required="required">
				@endif
			</div>
		</div>
	</div>
</div>

<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
				<label for="type_driver">Tipo de vehiculo</label>
				<select name="type_driver" id="type_driver" class="form-control">
					<option value="0" @if($data->type_driver == 0) selected @endif>Auto</option>
					<option value="1" @if($data->type_driver == 1) selected @endif>Motocicleta</option>
					<option value="2" @if($data->type_driver == 2) selected @endif>Bicicleta</option>
				</select>
            </div>

            <div class="form-group col-md-6">
                <label for="max_range_km">Rango máximo de entrega</label>
                <input type="text" name="max_range_km" id="max_range_km" value="{{$data->max_range_km}}" class="form-control">
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
