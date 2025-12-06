@extends('layouts.app')

@section('title', 'إنشاء بطاقة جديدة')

@section('content')
<div class=" mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">إنشاء بطاقة جديدة</div>

                <div class="card-body">
                    <form action="{{ route('cards.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="amount" class="form-label">القيمة</label>
                            <input type="number" step="0.01" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">إنشاء بطاقة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
