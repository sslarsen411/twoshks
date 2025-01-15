@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/404-error.svg')}}" alt="Image for error 404 not found">
@stop
@section('message', __('Not Found'))
@section('description' ,)
<p class="my-5">
    Yikes! We tried and tried, but we can’t find the page "<strong>{{ Request::segment(1)}}</strong>."
    The site administrator may have removed it, changed its location, or made it otherwise unavailable.
</p>
@stop

