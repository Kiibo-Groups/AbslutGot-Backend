 
<h4>Sobre nosotros</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        {{-- <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Imagen</label>
                <input type="file" name="about_img" class="form-control">
                @if($data->about_img)
                <br><img src="{{ Asset('upload/page/'.$data->about_img) }}" height="60"> 
                <a href="{{ Asset($form_url.'/add?remove=about_img') }}" onclick="return confirm('Are you sure?')" style="color:red">Eliminar</a>
                @endif
            </div>
        </div> --}}

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Descripción</label> 
                <textarea class="tinymce-editor" name="about_us">
                    {!! $data->about_us !!}
                </textarea> 
            </div>
        </div>
    </div>
</div>

<h4>Cómo trabajamos</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        {{-- <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Imagen</label>
                <input type="file" name="how_img" class="form-control" @if(!$data->id) required="required" @endif>

                @if($data->how_img)
                <br><img src="{{ Asset('upload/page/'.$data->how_img) }}" height="60">
                <a href="{{ Asset($form_url.'/add?remove=how_img') }}" onclick="return confirm('Are you sure?')" style="color:red">Remove</a>
                @endif
            </div>
        </div> --}}

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Descripción</label>
                <textarea class="tinymce-editor" name="how">{!! $data->how !!}</textarea>
            </div>
        </div>
    </div>
</div>

<h4>Faq's</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Descripción</label>
                <textarea class="tinymce-editor" name="faq">{!! $data->faq !!}</textarea>
                
            </div>
        </div>
    </div>
</div>

<h4>Contacta con nosotros</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="inputEmail6">Descripción</label>
                <textarea class="tinymce-editor" name="contact_us">{!! $data->contact_us !!}</textarea>
            </div>
        </div>
    </div>
</div>
 
<button type="submit" class="btn btn-success btn-cta">Guardar cambios</button><br><br>
