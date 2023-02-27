
<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">selecciona una categoría <small>(Debes Agregarlas en area de categorias)</small></label>
		<select name="cate_id" class="form-select" required="required">
		<option value="">Select</option>
		@foreach($category as $cat)
			<option value="{{$cat->id}}" @if($data->category_id == $cat->id) selected @endif>{{$cat->name}}</option>
		@endforeach
		</select>
	</div>

	<div class="col-md-6">
		<label for="inputEmail6">Nombre</label>
		{!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control'])!!}
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Descripción</label>
		{!! Form::text('description',null,['id' => 'code','placeholder' => 'Item Description','class' => 'form-control'])!!}
	</div>

	<div class="col-md-6">
		<label for="inputEmail6">Estado</label>
		<select name="status" class="form-select">
			<option value="0" @if($data->status == 0) selected @endif>Active</option>
			<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
		</select>
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Imagen</label>
		<input type="file" name="img" class="form-control" @if(!$data->id) required="required" @endif>
	</div>

	<div @if($user->subtype == 0) class="col-md-6" @else class="col-md-3" @endif>
		<label for="inputEmail6">Orden de clasificación</label>
		{!! Form::number('sort_no',null,['id' => 'code','class' => 'form-control'])!!}
	</div>

	@if($user->subtype == 1)
	<div class="col-md-3">
		<label for="inputEmail6">Cantidad</label>
		{!! Form::number('qty',null,['id' => 'code','class' => 'form-control'])!!}
	</div>
	@endif
</div>

<div class="row mb-3">
	<div class="col-md-6">
		<label for="inputEmail6">Precio</label>
		{!! Form::text('small_price',null,['id' => 'code','placeholder' => 'Precio final al consumidor','class' => 'form-control'])!!}
	</div>

	<div class="col-md-6">
		<label for="last_price">Precio antes del descuento </label>
		{!! Form::text('last_price',null,['id' => 'last_price','placeholder' => 'Precio antes del descuento','class' => 'form-control'])!!}
	</div>
</div> 

@if($user->subtype == 0)
<div class="row mb-3">
	<div class="col-md-12">
		<label for="inputEmail6">Agregar Conjunto de Modificadores</label>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="item_id" value="{{ $data->id }}">


		<select name="a_id[]" class="form-select js-select2" multiple="true">
		@foreach($cates as $cate)
			@if($cate->type == 1)
			<option value="{{ $cate->id }}" @if(in_array($cate->id,$arrayCate)) selected @endif>
				{{ $cate->name }}
				@if($cate->id_element != '')
					<small>({{$cate->id_element}})</small>
				@endif
			</option>
			@endif
		@endforeach
		</select>
	</div>
</div>	
@endif

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>