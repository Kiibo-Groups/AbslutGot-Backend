@extends('user.layout.main')

@section('title') Subir archivo de Excel @endsection
 
@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 mt-2">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        {!! Form::open(['url' => [Asset('import')],'files' => true],['class' => 'col s12']) !!} 
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="import_lbl">
                                        Seleccionel Archivo<br />
                                        <small>(Recuerde ingresar los mismos campos en orden correspondiente)</small>
                                    </label>
                                    <input type="file" id="import_lbl" class="form-control" name="file" required="required">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-cta">Subir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection