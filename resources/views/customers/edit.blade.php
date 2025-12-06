@extends('layouts.app')

@section('title', 'تعديل بيانات الزبون')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">تعديل بيانات الزبون</div>
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="city_id">المدينة</label>
                            <select class="form-control select2" id="city_id" name="city_id" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $customer->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ $customer->phone_number }}" placeholder="مثال: +966501234567" required>
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
