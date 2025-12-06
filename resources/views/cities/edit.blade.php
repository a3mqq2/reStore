@extends('layouts.app')

@section('title', 'تعديل مدينة')

@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">تعديل بيانات المدينة</div>
        <div class="card-body">
            <form action="{{ route('cities.update', $city->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">اسم المدينة</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $city->name }}" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</div>
@endsection
