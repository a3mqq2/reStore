@extends('layouts.app')

@section('title', 'تعديل الحساب')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit"></i> تعديل الحساب
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('accounts.update', $account) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="account_category_id" class="form-label">التصنيف <span class="text-danger">*</span></label>
                        <select name="account_category_id" id="account_category_id" class="form-select" required>
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('account_category_id', $account->account_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">عنوان الحساب <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $account->title) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $account->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">اسم المستخدم / البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $account->username) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" value="{{ old('password', $account->password) }}" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">السعر (د.ل) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" value="{{ old('price', $account->price) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="available" {{ old('status', $account->status) == 'available' ? 'selected' : '' }}>متاح</option>
                            <option value="sold" {{ old('status', $account->status) == 'sold' ? 'selected' : '' }}>مباع</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes', $account->notes) }}</textarea>
                </div>

                @if($account->customer)
                    <div class="alert alert-info">
                        <strong><i class="fas fa-info-circle"></i> معلومات البيع:</strong><br>
                        الزبون: <a href="{{ route('customers.show', $account->customer) }}">{{ $account->customer->name }}</a><br>
                        تاريخ البيع: {{ $account->sold_at->format('Y-m-d H:i') }}
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection
