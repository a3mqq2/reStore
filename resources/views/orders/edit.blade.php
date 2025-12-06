@extends('layouts.app')

@section('title', 'تعديل الطلب')

@section('content')
<div class="container mt-4">
    <order-component :order-id="{{ $order->id }}"></order-component>
</div>
@endsection
