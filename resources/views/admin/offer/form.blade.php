<div class="card py-3 m-b-30">
	<div class="card-body">
		<div class="row mb-3">
			<div class="col-md-6">
				<label for="inputEmail6">Código del cupon</label>
				{!! Form::text('code',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
			</div>
			<div class="col-md-6">
				<label for="inputEmail6">Descripción</label>
				{!! Form::text('description',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
			</div>
		</div>

		<div class="row mb-3">
			<div class="col-md-6">
				<label for="inputEmail6">Valor minimo del carrito <small>(opcional)</small></label>
				{!! Form::number('min_cart_value',null,['id' => 'code','class' => 'form-control'])!!}
			</div>

			<div class="col-md-6">
				<label for="inputEmail6">Tipo de descuento</label>
				<select name="type" class="form-select">
					<option value="0" @if($data->type == 0) selected @endif>in %</option>
					<option value="1" @if($data->type == 1) selected @endif>Fixed Amount</option>
				</select>
			</div>
		</div>

		<div class="row mb-3">
			<div class="col-md-3">
				<label for="inputEmail6">Valor del descuento</label>
				{!! Form::number('value',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
			</div>

			<div class="col-md-3">
				<label for="inputEmail6">Status</label>
				<select name="status" class="form-select">
					<option value="0" @if($data->status == 0) selected @endif>Active</option>
					<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
				</select>
			</div>

			<div class="col-md-6">
				<label for="inputEmail6">Imagen Descriptiva</label>
				<input type="file" name="img" class="form-control"  @if(!$data->id) required="required" @endif>
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-md-12">
				<label for="inputEmail6">Seleccionar Negocios</label>
				<select name="store[]" class="form-select js-select2" multiple="true">
					<option value="">All Store</option>
					@foreach($users as $user)
					<option value="{{ $user->id }}" @if(in_array($user->id,$array)) selected @endif>{{ $user->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div> 

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>

