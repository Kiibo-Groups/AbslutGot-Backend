 
<div class="row mb-3">
	@if($user->type_store == 0)
	<div class="col-md-6">
		<label for="inputEmail6">Tipo</label>
		<select name="type" class="form-control" id="type_cat" onchange="changeType();">
			<option value="0" @if($data->type == 0) selected @endif>De Productos</option>
			<option value="1" @if($data->type == 1) selected @endif>De Piezas</option>
		</select>
	</div>
	@else
	<input type="hidden" name="type" value="0" class="form-control">
	@endif
	
	<div class="col-md-6" id="name_cat">
		<label for="inputEmail6">Nombre</label>
		<input type="text" name="name" id="name" value="{{$data->name}}" placeholder="Nombre" class="form-control">
	</div>

	<div class="col-md-6 addon_type">
		<label for="description">Descripción</label>
		<input type="text" name="description" value="{{$data->name}}" id="description" placeholder="Descripción (Ej.- Deseas Ensalda?)" class="form-control">
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6 addon_type">
		<label for="inputEmail6">¿Es Requerido?</label>
		<select name="required" class="form-control">
			<option value="0" @if($data->required == 0) selected @endif>Opcional</option>
			<option value="1" @if($data->required == 1) selected @endif>Obligatorio</option>
		</select>
	</div> 
	<div class="col-md-6 addon_type">
		<label for="inputEmail6">Opción Multiple/Unico</label>
		<select name="single_option" class="form-control" id="single_option" onchange="changeOption();">
			<option value="0" @if($data->single_option == 0) selected @endif>Opción Unica</option>
			<option value="1" @if($data->single_option == 1) selected @endif>Opción Múltiple</option>
		</select>
	</div> 
</div>

<div class="row mb-3">
	<div class="col-md-6 max_options">
		<label for="inputEmail6">Maximo de opciones</label>
		<input type="number" name="max_options" min="0" value="{{$data->max_options}}" placeholder="indique 0 para no poner restricción" class="form-control">
	</div> 

	<div class="col-md-6">
		<label for="inputEmail6">Identificador de elemento <small>(Solo tu lo veras)</small></label>
		<input type="text" name="id_element" value="{{$data->id_element}}" placeholder="Indica un texto descriptivo para ubicar esta categoria." class="form-control">
	</div> 
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Status</label>
		<select name="status" class="form-control">
			<option value="0" @if($data->status == 0) selected @endif>Active</option>
			<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
		</select>
	</div>

	<div class="col-md-6">
		<label for="inputEmail6">Orden de clasificación</label>
		{!! Form::number('sort_no',null,['id' => 'code','placeholder' => '1','class' => 'form-control'])!!}
	</div>
</div>  

<button type="submit" class="btn btn-success btn-cta">Guardar cambios</button>


<script type="text/javascript">
	let wrap_type = document.getElementsByClassName('addon_type');
	let name_cat  = document.getElementById('name_cat');
	let type_cat  = document.getElementById("type_cat");
	let single_option = document.getElementById('single_option');
	let max_options = document.getElementsByClassName('max_options')[0];

	changeSelect(wrap_type,name_cat,type_cat);
	changeOptions(single_option,max_options);

	function changeType() { 
		changeSelect(wrap_type,name_cat,type_cat);
	}

	function changeOption() {
		changeOptions(single_option,max_options);
	}

	function changeSelect(wrap_type,name_cat,type_cat)
	{
	
		if (type_cat.value == 1) {
			name_cat.style.display = "none";
			wrap_type[0].style.display = 'block';
			wrap_type[1].style.display = 'block';
			wrap_type[2].style.display = 'block';

			changeOptions(single_option,max_options)
		}else {
			name_cat.style.display = "block";
			wrap_type[0].style.display = 'none';
			wrap_type[1].style.display = 'none';
			wrap_type[2].style.display = 'none';
			max_options.style.display = "none";
		}
	}

	function changeOptions(single_option,max_options) {
		console.log(type_cat.value);
		if (single_option.value == 1) {
			max_options.style.display = "block";
		}else {
			max_options.style.display = "none";
		}
	}
</script>

<style>

	.addon_type, .max_options {
		display:none;
	}
</style>