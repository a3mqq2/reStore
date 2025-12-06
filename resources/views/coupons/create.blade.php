@extends('layouts.app')

@section('title', 'إنشاء كوبون جديد')

@section('content')
<div class="">
    <div class="">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    إنشاء كوبون جديد
                </div>
                <div class="card-body">
                    <form action="{{ route('coupons.store') }}" method="POST" class="row">
                        @csrf

                        <!-- الكود -->
                        <div class="form-group col-md-4">
                            <label for="code">الكود</label>
                            <input type="text" name="code" class="form-control" id="code" placeholder="كود الكوبون" value="{{ old('code') }}">
                            {{-- <small class="form-text text-muted">اترك الحقل فارغاً ليتم توليد كود عشوائي.</small> --}}
                        </div>

                        <!-- نوع الخصم -->
                        <div class="form-group  col-md-4">
                            <label for="discount_type">نوع الخصم</label>
                            <select name="discount_type" id="discount_type" class="form-control" required>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>مبلغ ثابت</option>
                            </select>
                        </div>

                        <!-- نسبة الخصم (%) -->
                        <div class="form-group  col-md-4" id="discount_percentage_group">
                            <label for="discount_percentage">نسبة الخصم (%)</label>
                            <input type="number" step="0.01" name="discount_percentage" class="form-control" id="discount_percentage" placeholder="نسبة الخصم" value="{{ old('discount_percentage') }}" min="0" max="100" required>
                        </div>

                        <!-- مبلغ الخصم -->
                        <div class="form-group  col-md-4" id="discount_amount_group" style="display: none;">
                            <label for="discount_amount">مبلغ الخصم</label>
                            <input type="number" step="0.01" name="discount_amount" class="form-control" id="discount_amount" placeholder="مبلغ الخصم" value="{{ old('discount_amount') }}" min="0">
                        </div>

                        <!-- الحالة -->
                 

                        <!-- اختيار المنتج (اختياري) -->
                        <div class="form-group  col-md-6">
                            <label for="product_id">المنتج (اختياري)</label>
                            <select name="product_id" id="product_id" class="form-control">
                                <option value="">-- اختر منتج --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- اختيار النوع الفرعي (اختياري) -->
                        <div class="form-group  col-md-6">
                            <label for="variant_id">النوع الفرعي (اختياري)</label>
                            <select name="variant_id" id="variant_id" class="form-control">
                                <option value="">-- اختر نوع فرعي --</option>
                                <!-- سيتم تحميل الأنواع الفرعية عبر AJAX -->
                            </select>
                        </div>

                        <!-- الوصف (اختياري) -->
                 
                        <!-- تاريخ البداية -->
                        <div class="form-group  col-md-6">
                            <label for="start_date">تاريخ البداية</label>
                            <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date') }}" required>
                        </div>

                        <!-- تاريخ النهاية -->
                        <div class="form-group  col-md-6">
                            <label for="end_date">تاريخ النهاية</label>
                            <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date') }}" required>
                        </div>


                        <div class="form-group  col-md-12">
                            <label for="description">الوصف (اختياري)</label>
                            <textarea name="description" class="form-control" id="description" placeholder="وصف الكوبون">{{ old('description') }}</textarea>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-success mt-3">إنشاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- تضمين مكتبة jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        /**
         * وظيفة لتبديل عرض حقول الخصم بناءً على نوع الخصم المحدد
         */
        function toggleDiscountFields() {
            var discountType = $('#discount_type').val();
            if (discountType === 'percentage') {
                $('#discount_percentage_group').show();
                $('#discount_percentage').attr('required', true);
                $('#discount_amount_group').hide();
                $('#discount_amount').attr('required', false);
            } else if (discountType === 'amount') {
                $('#discount_percentage_group').hide();
                $('#discount_percentage').attr('required', false);
                $('#discount_amount_group').show();
                $('#discount_amount').attr('required', true);
            }
        }

        // تنفيذ الوظيفة عند تحميل الصفحة
        toggleDiscountFields();

        // تنفيذ الوظيفة عند تغيير نوع الخصم
        $('#discount_type').change(function() {
            toggleDiscountFields();
        });

        /**
         * وظيفة لتحميل الأنواع الفرعية بناءً على المنتج المحدد عبر AJAX
         */
        function loadVariants(productId, selectedVariantId = null) {
            var variantSelect = $('#variant_id');
            variantSelect.empty();
            variantSelect.append('<option value="">-- اختر نوع فرعي --</option>');

            if (productId) {
                $.ajax({
                    url: '{{ route("getVariants", ":productId") }}'.replace(':productId', productId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.variants.length > 0) {
                            $.each(data.variants, function(index, variant) {
                                var selected = (variant.id == selectedVariantId) ? 'selected' : '';
                                variantSelect.append('<option value="' + variant.id + '" ' + selected + '>' + variant.name + '</option>');
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.error);
                    }
                });
            }
        }

        // تنفيذ الوظيفة عند تغيير المنتج المحدد
        $('#product_id').change(function() {
            var productId = $(this).val();
            loadVariants(productId);
        });

        // إذا كان هناك منتج محدد سابقاً (مثلاً بعد فشل التحقق من صحة النموذج)
        @if(old('product_id'))
            loadVariants('{{ old('product_id') }}', '{{ old('variant_id') }}');
        @endif
    });
</script>
@endsection
