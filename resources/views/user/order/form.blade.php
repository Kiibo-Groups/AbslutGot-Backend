<div class="card py-3 m-b-30">
<div class="card-body">

<h4>Detalles del usuario</h4><br>

<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail6">Seleccion de tienda</label>
<select name="store_id" class="form-control" required="required" id="store_id">
@foreach($users as $u)
@if($u->id == Auth::user()->id)
<option value="{{ $u->id }}">{{ $u->name }}</option>
@endif
@endforeach
</select>
</div>

<div class="form-group col-md-6">
<label for="inputEmail6">Phone</label>
{!! Form::text('phone',$data->phone,['id' => 'code','required' => 'required','class' => 'form-control','onchange' => 'getUser(this.value)'])!!}
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label for="inputEmail6">User Name</label>
{!! Form::text('name',$data->name,['id' => 'name','required' => 'required','class' => 'form-control'])!!}
</div>

<div class="form-group col-md-6">
<label for="inputEmail6">Address</label>
{!! Form::text('address',$data->address,['id' => 'address','required' => 'required','class' => 'form-control'])!!}
</div>

<div class="form-group col-md-6">
	<input type="text" name="lat" id="lat" hidden>
	<input type="text" name="lng" id="lng" hidden>
</div>
</div>
</div>
</div>

<div class="card py-3 m-b-30">
<div class="card-body">

<h4>Order Details</h4><br>

@if($data->id)

@include('user.order.item')

@endif

<span id="item"></span>

<br>
<button type="button" class="btn btn-info" onClick="AddMore();">Add Item</button>

</div>
</div>

<button type="submit" class="btn btn-success btn-cta">Save changes</button>

<SCRIPT>

function getUser(id)
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
	var t = JSON.parse(xmlhttp.responseText);
	console.log(t);
	if(t.name)
	{
		document.getElementById("name").value=t.name;
	}
	
	if(t.address)
	{
		document.getElementById("address").value=t.address;
	}

	if(t.lat)
	{
		document.getElementById("lat").value=t.lat;
	}
	if(t.lng)
	{
		document.getElementById("lng").value=t.lng;
	}
}
}
	xmlhttp.open("GET","{{ Asset('/getUser') }}/"+id,true);
	xmlhttp.send();
}

function AddMore() {
    
    var sid = document.getElementById("store_id").value;
	
	$("<DIV>").load("{{ Asset('/orderItem?store_id=') }}"+sid, function() {
	
	$("#item").append($(this).html());
	
	});


}
function Remove(id) {
	$(id).remove();
}
</SCRIPT>
<br>