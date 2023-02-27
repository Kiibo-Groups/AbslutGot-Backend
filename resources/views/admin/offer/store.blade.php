<div class="modal fade modal-slide-right" id="slideRightModal{{ $row->id }}" tabindex="-1" role="dialog aria-labelledby="slideRightModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="slideRightModalLabel">Assign Restaurants</h5>
<button type="button" class="close" data-dismiss="modal"
aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">

<form action="{{ Asset($form_url) }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="offer_id" value="{{ $row->id }}">


<div class="row">
@foreach($users as $user)
<div class="col-md-6">
<div class="custom-control custom-checkbox">
<input type="checkbox" class="custom-control-input" id="customCheck{{ $user->id.$row->id }}" value="{{ $user->id }}" name="user_id[]" @if(in_array($user->id,$assign->getAssigned($row->id))) checked @endif>
<label class="custom-control-label" for="customCheck{{ $user->id.$row->id }}">{{ $user->name }}</label>
</div><br>
</div>
@endforeach

</div>



</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">
Close
</button>
<button type="submit" class="btn btn-primary">Save changes</button>
</form>
</div>
</div>
</div>
</div>


</div>