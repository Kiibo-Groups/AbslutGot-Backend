
<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Name</label>
{!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control'])!!}
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail6">Avilable From</label>
<select name="start_time" class="form-control">
<option>Select Time</option>
@php($ot = 0)

@while($ot < 23)

@php($ot++)

<option value="{{ $ot }}" @if($data->start_time == $ot) selected @endif>{{ $ot }}</option>
<option value="{{ $ot.":30" }}" @if($data->start_time == $ot.':30') selected @endif> {{ $ot }}:30</option>

@endwhile
<option value="00" @if($data->start_time == '00') selected @endif>00</option>
</select>
</div>

<div class="form-group col-md-6">
<label for="inputEmail6">Avilable Till</label>
<select name="end_time" class="form-control">
<option>Select Time</option>
@php($ct = 0)

@while($ct < 23)

@php($ct++)

<option value="{{ $ct }}" @if($data->end_time == $ct) selected @endif>{{ $ct }}</option>
<option value="{{ $ct.":30" }}" @if($data->end_time == $ct.":30") selected @endif>{{ $ct }}:30</option>

@endwhile
<option value="00" @if($data->end_time == '00') selected @endif>00</option>
</select>
</div>
</div>

<button type="submit" class="btn btn-success btn-cta">Save changes</button>
