@if(isset($langs))

<ul class="nav nav-tabs" id="myTab" role="tablist">

<ul class="nav nav-tabs" id="myTab" role="tablist">

@foreach($langs as $lng)

<li class="nav-item">
<a class="nav-link @if($lng['id'] == 0) active @endif" id="{{ $lng['name'] }}-tab-z" data-toggle="tab" href="#lang{{ $lng['id'] }}" role="tab"
aria-controls="lang{{ $lng['id'] }}" aria-selected="false"><img src="{{ Asset('upload/language/'.$lng['icon']) }}" height="20"> {{ $lng['name'] }}</a>
</li>

@endforeach

</ul>


@else

<ul class="nav nav-tabs" id="myTab" role="tablist">
<li class="nav-item">
<a class="nav-link active" id="home-tab-z" data-toggle="tab" href="#home" role="tab"
aria-controls="home" aria-selected="true"><img src="{{ Asset('upload/en.png') }}" height="20"> English</a>
</li>

@php($langs = DB::table('language')->orderBy('sort_no','ASC')->get())

@foreach($langs as $lng)

<li class="nav-item">
<a class="nav-link" id="{{ $lng->name }}-tab-z" data-toggle="tab" href="#lang{{ $lng->id }}" role="tab"
aria-controls="lang{{ $lng->id }}" aria-selected="false"><img src="{{ Asset('upload/language/'.$lng->icon) }}" height="20"> {{ $lng->name }}</a>
</li>

@endforeach

</ul>


@endif