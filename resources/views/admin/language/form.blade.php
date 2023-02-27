
<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail6">Name</label>
{!! Form::text('name',null,['id' => 'code','required' => 'required','class' => 'form-control'])!!}
</div>

<div class="form-group col-md-6">
<label for="inputEmail6">Type</label>
<select name="type" class="form-control">
	<option value="0" @if($data->type == 0) selected @endif>Left To Right</option>
	<option value="1" @if($data->type == 1) selected @endif>Right To Left</option>
</select>
</div>
</div>


<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail6">Icon <small>(512x512)</small></label>
<input type="file" name="img" class="form-control">
</div>

<div class="form-group col-md-6">
<label for="inputEmail6">Sort No</label>
{!! Form::number('sort_no',null,['id' => 'code','class' => 'form-control'])!!}
</div>
</div>

<button type="submit" class="btn btn-success btn-cta">Save changes</button>
