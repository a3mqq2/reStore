@extends('layouts.app')

@section('title', 'إنشاء مدينة جديدة')

@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">إنشاء مدينة جديدة</div>
        <div class="card-body">
            <form action="{{ route('cities.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">اسم المدينة</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="اسم المدينة" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2">إنشاء</button>
            </form>
        </div>
    </div>
</div>
@endsection
