@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('image', )
<img src="{{ asset('https://cdn.mojoimpact.com/errors/402-Error.svg')}}" alt="Image for error 402 payment required">
@stop
@section('message', __('Payment Required'))
@section('description' ,)
<p class="my-5 text-lg">
    This error may have occured because:
</p>
<ul class="list-disc text-large">
    <li>
        A declined payment: A card provider might decline a payment for security reasons, especially when making an international purchase. 
    </li>
    <li>
        Expired subscription: Your subscription might have expired, preventing access to content. 
    </li>
    <li>
        Insufficient funds: There might not be enough funds available to complete the payment.
    </li>
</ul>
@stop