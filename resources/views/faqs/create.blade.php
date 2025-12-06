@extends('layouts.app')

@section('title', 'إضافة سؤال شائع جديد')

@section('content')
<div class="mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">إضافة سؤال شائع جديد</div>

                <div class="card-body">
                    <form action="{{ route('faqs.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="question" class="form-label">السؤال</label>
                            <input type="text" name="question" id="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question') }}" required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="answer" class="form-label">الإجابة</label>
                            <textarea name="answer" id="answer" rows="5" class="form-control @error('answer') is-invalid @enderror" required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">ترتيب العرض</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input @error('is_active') is-invalid @enderror" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">حفظ السؤال الشائع</button>
                        <a href="{{ route('faqs.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
