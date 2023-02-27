
<div class="row mb-3">
    <div class="col-md-12">
        <label for="inputEmail6">Ciudad en que se mostrara</label>
        <select name="city_id" class="form-select">
            <option value="">Todas las ciudades</option>
            @foreach($citys as $city)
            <option value="{{ $city->id }}" @if($data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="inputEmail6">Posición del banner</label>
        <select name="position" class="form-select" required="required">
            <option value="0" @if($data->position == 0) selected @endif>Principal (270px * 140px)</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="inputEmail6">Imagen</label>
        <input type="file" name="img" class="form-control" @if(!$data->id) required="required" @endif>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="inputEmail4">Negocios <small>(Aparecerán en la lista al hacer clic en el banner)</small></label>
        <select name="store[]" class="form-select js-select2" multiple>
            <option value="">Todos</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @if(isset($array) && in_array($user->id,$array)) selected @endif>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="inputEmail6">Status</label>
        <select name="status" class="form-select" required="required">
            <option value="0" @if($data->status == 0) selected @endif>Activo</option>
            <option value="1" @if($data->status == 1) selected @endif>No Disponible</option>
        </select>
    </div>
</div>

@if($data->id)
<img src="{{ Asset('upload/banner/'.$data->img) }}" height="100"><br><br>
@endif

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
