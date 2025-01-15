@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/401-Error.svg')}}" alt="Image for error 401 unauthorized">
@stop
@section('message', __('Unauthorized'))
@section('description' ,)
<p>
    There is a lack of valid authentication credentials to access this resource.
</p>
<p>
    Your credentials are either missing or expired. Try logging in again.
</p>
@stop
