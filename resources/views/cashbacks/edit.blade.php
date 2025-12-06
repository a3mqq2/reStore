@extends('layouts.app')

@section('title', 'تعديل استرداد نقدي')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">تعديل استرداد نقدي</div>

                <div class="card-body">
                    <form class="row" id="cashbackForm" action="{{ route('cashbacks.update', $cashback->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 col-12">
                            <label for="product_name" class="form-label">اسم المنتج</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $cashback->product_name }}" required>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="product_image" class="form-label">صورة المنتج</label>
                            <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                            @if($cashback->product_image)
                                <img src="{{ asset('storage/' . $cashback->product_image) }}" alt="{{ $cashback->product_name }}" class="img-fluid mt-2" style="max-width: 150px;">
                            @endif
                        </div>

                        <div class="mb-3 col-12">
                            <label for="product_details" class="form-label">تفاصيل المنتج</label>
                            <textarea class="form-control" id="product_details" name="product_details" rows="4">{{ $cashback->product_details }}</textarea>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="amount" class="form-label">مجموع النقاط</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $cashback->amount }}" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-success">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
