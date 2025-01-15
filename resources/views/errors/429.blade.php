@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('image', )
<img src="{{  asset('https://cdn.mojoimpact.com/errors/429-error.svg')}}" alt="Image for error 429 to many requests">
@stop
@section('message', __('Too Many Requests'))
@section('description' ,)
<p>
    Oops! Too many requests. Try again later.
</p>
@stop
