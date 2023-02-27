<div class="form-row">
    <div class="form-group col-md-6">
        <label for="title">
            Nombre del programa
        </label>
        <input type="text" name="title" value="{{$data->title}}" id="title" class="form-control">
    </div>

    <div class="form-group col-md-6">
        <label for="visits">
            Visitas
        </label>
        <input type="text" name="visits" value="{{$data->visits}}" id="visits" class="form-control">
    </div>
    
    <div class="form-group col-md-6">
        <label for="consum_min">
            Consumo minimo
        </label>
        <input type="text" name="consum_min" value="{{$data->consum_min}}" id="consum_min" class="form-control">
    </div>

    <div class="form-group col-md-6">
        <label for="inputEmail6">
            Item que aplica
        </label> 
        <select name="items[]" class="form-control js-select2" multiple="true">
            @foreach($items as $its)
                <option value="{{ $its->id }}" @if(in_array($its->id,$arrayItems)) selected @endif>
                    {{ $its->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="inputEmail6">
            Descripción
        </label>
        <textarea name="descript" id="descript" class="form-control">{{$data->descript}}</textarea>
    </div>
</div>

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>    