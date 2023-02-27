<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Nivel de categoria</label>
		<select name="type_cat" class="form-select">
			<option value="0" @if($data->type_cat == 0) selected @endif>Principal</option>
			<option value="1" @if($data->type_cat == 1) selected @endif>Categoria</option>
			<option value="2" @if($data->type_cat == 2) selected @endif>SubCategoria</option>
		</select>
	</div>

	<div class="col-md-3">
		<label for="inputEmail6">Categoria Principal</label>
		<select name="id_cp" class="form-select">
			<option value="0">No Aplica</option>
			@foreach ($cat_p as $cp)
			<option value="{{$cp->id}}" @if($data->id_cp == 0) selected @endif>{{$cp->name}}</option>
			@endforeach
		</select>
	</div>

	<div class="col-md-3">
		<label for="inputEmail6">Categoria</label>
		<select name="id_c" class="form-select">
			<option value="0">No Aplica</option>
			@foreach ($cat_c as $cp)
			<option value="{{$cp->id}}" @if($data->id_c == 0) selected @endif>{{$cp->name}}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Nombre</label>
		{!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control'])!!}
	</div>

	<div class="col-md-6">
		<label for="inputEmail6">Status</label>
		<select name="status" class="form-select">
			<option value="0" @if($data->status == 0) selected @endif>Active</option>
			<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
		</select>
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Orden de clasificación</label>
		{!! Form::number('sort_no',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control'])!!}
	</div>

	<div class="col-md-6">
		<label for="inputEmail6">Imagen Descriptiva (512px * 512px)</label>
		<input type="file" name="img" class="form-control" @if(!$data->id) required="required" @endif>
	</div>
</div> 

<button type="submit" class="btn btn-success btn-cta">Guardar</button>
