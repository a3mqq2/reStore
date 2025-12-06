@extends('layouts.app')

@section('title', 'تعديل البطاقة')

@section('content')
<div class=" mt-4">
    <div class="row ">
        <div class="col-md-12">
            <a href="{{ route('cards.index') }}" class="btn btn-primary mb-4">
                <i class="fas fa-arrow-left"></i> العودة إلى القائمة
            </a>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-edit"></i> تعديل البطاقة
                </div>
                <div class="card-body">
                    <form action="{{ route('cards.update', $card->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="amount" class="form-label">المبلغ</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount', $card->amount) }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the edit card form */
    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .form-label {
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
