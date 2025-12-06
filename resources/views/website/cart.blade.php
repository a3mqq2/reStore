@extends('layouts.web')

@section('title', 'سلة المشتريات')

@section('content')
<div class="container mt-5" id="app">
    <div class="row p-3">
        <div class="col-md-12">
            <cart-component :customer-id="{{ json_encode(Cookie::get('customerId')) }}" :session="{{json_encode(Cookie::get('guest_session_id'))}}" :payment="{{ request('payment_method_id') ? request('payment_method_id') : $currentPaymentMethod }}" ref="cartComponent" />
        </div>
    </div>
</div>
@endsection
