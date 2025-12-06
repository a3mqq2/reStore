@extends('layouts.web')

@section('title', 'عرض بيانات المنتج')

@section('content')
<div id="app">
    <product-details :session-id="{{ json_encode(Cookie::get('guest_session_id')) }}" :product-id="{{ $product->id }}" :payment-method-id="{{ $currentPaymentMethod }}" customer-id="{{auth('customer')->check() ? auth('customer')->user()->id : "" }}" ></product-details>
</div>
@endsection
