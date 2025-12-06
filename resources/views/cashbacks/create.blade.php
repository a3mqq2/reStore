@extends('layouts.app')

@section('title', 'إضافة استرداد نقدي جديد')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">إضافة استرداد نقدي جديد</div>

                <div class="card-body">
                    <form class="row" id="cashbackForm" action="{{ route('cashbacks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 col-12">
                            <label for="product_name" class="form-label">اسم المنتج</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="product_image" class="form-label">صورة المنتج</label>
                            <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                        </div>

                        <div class="mb-3 col-12">
                            <label for="product_details" class="form-label">تفاصيل المنتج</label>
                            <textarea class="form-control" id="product_details" name="product_details" rows="4"></textarea>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="amount" class="form-label">مجموع النقاط</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-success">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
