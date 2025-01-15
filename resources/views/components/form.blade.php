@props([
    'method' => 'POST',
    'action' => '',
    'enctype'=> 'application/x-www-form-urlencoded'
])
<form wire:submit.prevent="submitForm" action="{{$action}}" method="{{$method === 'GET'? 'GET':'POST'}}" enctype="{{$enctype}}" {{ $attributes }} >
@csrf
@php

@endphp
@if (! in_array($method, ['GET', 'POST']))
    @method($method)
@endif


{{$slot}}

</form>