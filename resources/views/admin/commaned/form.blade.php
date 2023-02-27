<input type="hidden" name="order_store" vale="false">
<input type="hidden" name="store_id" value="0">

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group @if(!$data->id) col-md-6 @else col-md-12 @endif">
		<label>Usuario</label>
		@if($data->id)
			<input type="text" class="form-control" value="{{$res->viewUserComm($data->id)}}" readonly="readonly">
		@else 
		<select name="user_id" id="user_id" class="form-control">
			@foreach ($users as $us)
				<option value="{{$us->id}}">{{$us->name}}</option>
			@endforeach 
        </select>
		@endif
	</div>

	@if(!$data->id)
	<div class="form-group col-md-6">
		<label>Ciudad</label>
		<select name="city_id" id="city_id" class="form-control">
			@foreach ($citys as $ct)
				<option value="{{$ct->id}}">{{$ct->name}}</option>
			@endforeach 
        </select>		
	</div>
	@endif
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="address_origin">Punto de recolección<br />
		@if(!$data->id) <small>(Arrastra el Punto A a su ubicación deseada)</small>@endif
		</label>
		<input type="text" name="address_origin" id="address_origin" class="form-control" @if(!$data->id) required @endif value="{{$data->address_origin}}" readonly="readonly">
		<input type="hidden" name="lat_orig" id="lat_orig">
		<input type="hidden" name="lng_orig" id="lng_orig">
	</div>

	<div class="form-group col-md-6">
		<label for="address_destin">Punto de entrega<br />
		@if(!$data->id) <small>(Arrastra el Punto B a su ubicación deseada)</small>@endif
		</label>
		<input type="text" name="address_destin" id="address_destin" class="form-control" @if(!$data->id) required @endif value="{{$data->address_destin}}" readonly="readonly">
		<input type="hidden" name="lat_dest" id="lat_dest">
		<input type="hidden" name="lng_dest" id="lng_dest">
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="first_instr">Instrucciones de recolección</label>
		<textarea name="first_instr" id="first_instr" class="form-control" cols="5" rows="5" @if($data->id) readonly="readonly" @endif>{{$data->first_instr}}</textarea>
	</div>

	<div class="form-group col-md-6">
		<label for="second_instr">Instrucciones de Entrega</label>
		<textarea name="second_instr" id="second_instr" class="form-control" cols="5" rows="5" @if($data->id) readonly="readonly" @endif>{{$data->second_instr}}</textarea>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="d_charges">Costos de envio</label>
		<input type="text" name="d_charges" id="d_charges" class="form-control" @if(!$data->id) required @endif  @if($data->id) value="${{ number_format($data->d_charges,2) }}" @endif readonly="readonly">
	</div>

	<div class="form-group col-md-6">
		<label for="total">Total a pagar</label>
		<input type="text" name="total" id="total" class="form-control" @if(!$data->id) required @endif @if($data->id) value="${{ number_format($data->total,2) }}" @endif readonly="readonly">
	</div>
</div>

@if(!$data->id)
<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-12">
		<a href="#" class="btn btn-warning btn-cta" id="cotiz_service" onclick="Getquotation()">
			Cotizar Servicio
		</a>

		<button type="submit" class="btn btn-success btn-cta">Solicitar Servicio</button>
	</div>
</div>
@endif