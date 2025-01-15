@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/419-status-code.png')}}" alt="Image for error 419 expired">
@stop
@section('message', __('Page Expired'))
@section('description' ,)
<p>
    Sorry! This session has expired.
</p>
@stop
