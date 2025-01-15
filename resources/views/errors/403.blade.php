@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/403-Error.svg')}}" alt="Image for error 403">
@stop
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('description' ,)
<p>
    You do not have permission to access this resource.
</p>
@stop