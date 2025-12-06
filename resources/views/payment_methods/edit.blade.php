@extends('layouts.app')

@section('title', 'تعديل طريقة الدفع')

@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">تعديل طريقة الدفع</div>
        <div class="card-body">
            <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">اسم طريقة الدفع</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $paymentMethod->name }}">
                </div>

                <div class="mb-3">
                    <label for="my_contact" class="form-label">رقم الهاتف / ID</label>
                    <input type="text" name="my_contact" class="form-control" id="my_contact" value="{{ $paymentMethod->my_contact }}">
                </div>


                <div class="mb-3">
                    <label for="">العملة</label>
                    <select name="currency_id" id="" class="form-control">
                        <option value="">حدد عمله</option>
                        @foreach ($currencies as $currency)
                            <option value="{{$currency->id}}" {{$paymentMethod->currency_id==$currency->id?"selected":""}} >{{$currency->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">صورة طريقة الدفع</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>
                <div class="mb-3">
                    @if ($paymentMethod->image)
                        <img src="{{ asset('images/payment_methods/' . $paymentMethod->image) }}" alt="{{ $paymentMethod->name }}" width="100">
                    @endif
                </div>
                <button type="submit" class="btn btn-success">تحديث</button>
            </form>
        </div>
    </div>
</div>
@endsection
