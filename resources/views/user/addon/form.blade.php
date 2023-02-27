
<div class="row mb-3">
    <div class="col-md-6">
        <label for="inputEmail6">selecciona una categoría <small>(Debes Agregarlas en area de <a href="{{ Asset(env('user').'/category') }}" style="color:blue;">categorias</a> )</small></label>
        <select name="cate_id" class="form-select" required="required">
            <option value="">Selecciona una categoría</option>
            @foreach($cates as $cate)
                @if($cate->type == 1)
                <option value="{{ $cate->id }}" @if($data->category_id == $cate->id) selected @endif>{{ $cate->name }}
                @if($cate->id_element != '')
                    <small>({{$cate->id_element}})</small>
                    @endif</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="inputEmail6">Nombre del complemento</label>
        {!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control','required' => 'required'])!!}
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="inputEmail6">Precio</label>
        {!! Form::text('price',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
    </div>
</div>

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
