@extends('errors::minimal')

@section('title', __('I\'m a Teapot'))
@section('code', '418')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/418-status-code.png')}}" alt="Image for error 418 I'm a teapot">
@stop
@section('message', __('I\'m a Teapot'))
@section('description' ,)
    <p class="mt-5">
        The requested body is short and stout.
    </p>
    <p>
        Tip me over and pour me out.
    </p>
@stop
