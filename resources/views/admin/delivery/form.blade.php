
<div class="form-row">
<input type="text" name="deliveryVia" value="admin" hidden>

<div class="form-group col-md-6">
<label for="name">Nombre</label>
{!! Form::text('name',null,['id' => 'name','class' => 'form-control','required' => 'required'])!!}
</div>
<div class="form-group col-md-6">
<label for="city_id">Ciudad</label>
<select name="city_id" id="city_id" class="form-control" required="required">
<option value="">Seleccionar Ciudad</option>
@foreach($citys as $city)
<option value="{{ $city->id }}" @if($data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
@endforeach
</select>
</div>
</div>

<div class="form-row">
	<div class="form-group col-md-6">
		<label for="phone">Telefono (This will be username)</label>
		{!! Form::text('phone',null,['id' => 'phone','class' => 'form-control','required' => 'required'])!!}
	</div>

	<div class="form-group col-md-6">
		<label for="rfc">RFC</label>
		<input type="text" id="rfc" name="rfc" value="{{$data->rfc}}" required class="form-control">
	</div>
</div>

<div class="form-row">
	
	<div class="form-group col-md-6">
		@if($data->id)
			<label for="pass_new">Cambiar Contraeña</label>
			<input type="password" id="pass_new" name="password" class="form-control">
		@else
			<label for="pass">Contraseña</label>
			<input type="password" id="pass" name="password" class="form-control" required="required">
		@endif
	</div>
	<div class="form-group col-md-6">
		<label for="status">Estado</label>
		<select name="status" id="status" class="form-control">
			<option value="0" @if($data->status == 0) selected @endif>Active</option>
			<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
		</select>
	</div>
</div>

