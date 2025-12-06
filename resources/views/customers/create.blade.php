@extends('layouts.app')

@section('title', 'إضافة زبون جديد')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">إضافة زبون جديد</div>
                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="city_id">المدينة</label>
                            <select class="form-control select2" id="city_id" name="city_id" required>
                                <option value="">اختر المدينة</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="مثال: +966501234567" required>
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
