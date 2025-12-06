@extends('layouts.app')

@section('title', 'إنشاء طريقة دفع جديدة')

@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">إنشاء طريقة دفع جديدة</div>
        <div class="card-body">
            <form action="{{ route('payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم طريقة الدفع</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="my_contact" class="form-label">رقم الهاتف / ID</label>
                    <input type="text" name="my_contact" class="form-control" id="my_contact" value="{{ old('my_contact') }}">
                </div>
                <div class="mb-3">
                    <label for="">العملة</label>
                    <select name="currency_id" id="" class="form-control">
                        <option value="">حدد عمله</option>
                        @foreach ($currencies as $currency)
                            <option value="{{$currency->id}}"  >{{$currency->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">صورة طريقة الدفع</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>
                <button type="submit" class="btn btn-success">حفظ</button>
            </form>
        </div>
    </div>
</div>
@endsection
