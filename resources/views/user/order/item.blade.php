@if($data->id)

@foreach($detail as $d)

@php($uid = rand(111,9999))

<div class="form-row" id="uid{{ $uid }}">

<input type="hidden" name="id[]" value="{{ $uid }}">

<div class="form-group col-md-4">
<label for="class_{{ $uid }}">Select Item</label>
<select name="item_id[]" class="form-control js-select2" required="required" onchange="getUnit{{ $uid }}(this.value)">
<option value="">Select</option>
@foreach($item as $i)
<option value="{{ $i->id }}" @if($i->id == $d->item_id) selected @endif>{{ $i->name }}</option>
@endforeach
</select>
</div>

<div class="form-group col-md-4">
<label for="class_{{ $uid }}">Select Unit</label>
<select name="unit[]" class="form-control" required="required" id="unit{{ $uid }}">
<option value="">Select</option>
@foreach($data->getUnit($d->item_id) as $u)
<option value="{{ $u['id'] }}" @if($u['id'] == $d->qty_type) selected @endif>{{ $u['name'] }}</option>
@endforeach
</select>
</div>

<div class="form-group col-md-3">
<label for="class_{{ $uid }}">Quantity</label>
<input type="text" name="qty[]" class="form-control" value="{{ $d->qty }}">
</div>

<div class="form-group col-md-1">
<br><a href="javascript::void()" onclick="Remove(uid{{ $uid }})" style="color: red;font-size: 23px;"><i class="mdi mdi-delete"></i></a>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.js-select2').select2();
});


function getUnit{{ $uid }}(id)
{

var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
	xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
	document.getElementById("unit{{ $uid }}").innerHTML=xmlhttp.responseText;
}
}
	xmlhttp.open("GET","{{ Asset('/getUnit') }}/"+id,true);
	xmlhttp.send();
}


</script>

@endforeach

@else

@php($uid = rand(111,9999))

<div class="form-row" id="uid{{ $uid }}">

<input type="hidden" name="id[]" value="{{ $uid }}">

<div class="form-group col-md-4">
<label for="class_{{ $uid }}">Select Item</label>
<select name="item_id[]" class="form-control js-select2" required="required" onchange="getUnit{{ $uid }}(this.value)">
<option value="">Select</option>
@foreach($item as $i)
<option value="{{ $i->id }}">{{ $i->name }}</option>
@endforeach
</select>
</div>

<div class="form-group col-md-4">
<label for="class_{{ $uid }}">Select Unit</label>
<select name="unit[]" class="form-control" required="required" id="unit{{ $uid }}">
<option value="">Select</option>
</select>
</div>

<div class="form-group col-md-3">
<label for="class_{{ $uid }}">Quantity</label>
<input type="text" name="qty[]" class="form-control">
</div>

<div class="form-group col-md-1">
<br><a href="javascript::void()" onclick="Remove(uid{{ $uid }})" style="color: red;font-size: 23px;"><i class="mdi mdi-delete"></i></a>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.js-select2').select2();
});


function getUnit{{ $uid }}(id)
{

var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
	xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
	document.getElementById("unit{{ $uid }}").innerHTML=xmlhttp.responseText;
}
}
	xmlhttp.open("GET","{{ Asset('/getUnit') }}/"+id,true);
	xmlhttp.send();
}


</script>

@endif
