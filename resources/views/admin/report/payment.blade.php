@extends('admin.layout.main')

@section('title') Reporting @endsection

@section('icon') mdi-send @endsection


@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-lg-10 mx-auto  mt-2">
<div class="card py-3 m-b-30">
<div class="card-body">
{!! Form::open(['url' => [$form_url],'method' => 'GET'],['class' => 'col s12']) !!}

<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail4">Select Store</label>
<select name="store_id" class="form-control" required="required">
<option value="">Select</option>
@foreach($data as $user)
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endforeach
</select>
</div>
</div>

<button type="submit" class="btn btn-success btn-cta">Download File</button>


</form>
</div>
</div>
</div>
</div>
</div>
</section>

@endsection