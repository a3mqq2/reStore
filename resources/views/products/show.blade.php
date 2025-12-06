@extends('layouts.app')

@section('title', 'عرض المنتج')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('products.index') }}" class="btn btn-primary mb-4">
                <i class="fas fa-arrow-left"></i> العودة إلى القائمة
            </a>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-info-circle"></i> تفاصيل المنتج
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="position-relative">
                                @if ($product->image && $product->image != '-')
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-fluid rounded" id="productImage">
                                @else
                                    <div class="text-center p-5 bg-light border rounded" id="productImagePlaceholder">لا توجد صورة</div>
                                @endif

                                <!-- زر تحديث الصورة السريع -->
                                <label for="quickImageUpload" class="btn btn-primary btn-sm position-absolute" style="top: 10px; right: 10px; cursor: pointer;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="quickImageUpload" accept="image/*" style="display: none;">

                                <!-- مؤشر التحميل -->
                                <div id="imageUploadLoader" class="position-absolute d-none" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">جاري التحميل...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3">
                            <h5 class="card-title"><i class="fas fa-box"></i> {{ $product->name }}</h5>
                            <p class="card-text"><strong><i class="fas fa-align-left"></i> الوصف:</strong> {{ $product->description }}</p>
                            <p class="card-text"><strong><i class="fas fa-tags"></i> الفئة:</strong> {{ $product->category->name }}</p>

                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                                <i class="fas fa-plus"></i> إضافة فئة
                            </button>

                            <div class="row mt-3">
                                @foreach ($product->variants as $variant)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border border-primary">
                                            <div class="card-body">
                                                <span><i class="fas fa-tag"></i> الاسم: {{$variant->name}}</span><br>
                                                @foreach ($variant->prices as $price)
                                                    <span><i class="fas fa-dollar-sign"></i> السعر ({{ $price->paymentMethod->name }}): {{$price->price}} {{ $price->paymentMethod->currency->symbol }}</span><br>
                                                @endforeach

                                                @if ($product->smileone_name)
                                                    <div class="mt-3 p-2 bg-warning bg-opacity-25 rounded">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fas fa-smile text-warning"></i>
                                                            <span class="fw-bold">نقاط SmileOne:</span>
                                                            <input type="number" step="0.01" class="form-control form-control-sm smileone-points-input"
                                                                   style="width: 100px;"
                                                                   data-variant-id="{{ $variant->id }}"
                                                                   value="{{ $variant->smileone_points ?? 0 }}">
                                                            <button class="btn btn-sm btn-warning save-smileone-points" data-variant-id="{{ $variant->id }}">
                                                                <i class="fas fa-save"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- معلومات الاسترداد -->
                                                @if($variant->redemption_value > 0 || $variant->is_redeemable)
                                                <div class="mt-3 p-2 bg-success bg-opacity-10 rounded border border-success">
                                                    <small class="d-block text-success fw-bold"><i class="fas fa-exchange-alt"></i> الاسترداد:</small>
                                                    @if($variant->redemption_value > 0)
                                                    <small class="d-block">يمنح: {{ $variant->redemption_value }} نقطة</small>
                                                    @endif
                                                    @if($variant->is_redeemable)
                                                    <small class="d-block text-primary"><i class="fas fa-check-circle"></i> قابل للاسترداد ({{ $variant->redemption_cost }} نقطة)</small>
                                                    @endif
                                                </div>
                                                @endif

                                                <!-- Toggle Active Status -->
                                                <div class="form-check form-switch mt-3">
                                                    <input class="form-check-input toggle-active" type="checkbox" id="toggleActive{{ $variant->id }}" data-id="{{ $variant->id }}" {{ $variant->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="toggleActive{{ $variant->id }}">
                                                        {{ $variant->is_active ? 'نشط' : 'غير نشط' }}
                                                    </label>
                                                </div>

                                                <a href="{{route('variants.edit', $variant->id)}}" class="btn btn-info btn-sm mt-3">تعديل <i class="fa fa-edit"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>
                    </div>
                </div>

                @if ($product->requirements->isNotEmpty())
                    <div class="card-footer">
                        <h5 class="mb-3 text-primary font-weight-bold">المتطلبات:</h5>
                        @foreach ($product->requirements as $requirement)
                            <p><strong>{{ $requirement->name }}:</strong> {{ $requirement->type === 'text' ? 'نص' : 'قائمة' }}</p>
                            @if ($requirement->type === 'list' && $requirement->listItems->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>عنصر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($requirement->listItems as $index => $listItem)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $listItem->item }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="card-footer text-right">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                            <i class="fas fa-trash-alt"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal for adding variant -->
<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVariantModalLabel">إضافة فئة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variantForm" action="{{ route('products.addVariant', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="variantName" class="form-label">اسم الفئة</label>
                        <input type="text" class="form-control" id="variantName" name="variantName" required>
                    </div>
                    @foreach ($paymentMethods as $paymentMethod)
                        <div class="mb-3">
                            <label for="price_{{ $paymentMethod->id }}" class="form-label">السعر ({{ $paymentMethod->name }})</label>
                            <input type="number" step="0.01" value="0" class="form-control" id="price_{{ $paymentMethod->id }}" name="prices[{{ $paymentMethod->id }}]" required>
                        </div>
                    @endforeach

                    @if ($product->smileone_name)
                    <div class="mb-3">
                        <label for="smileone_points" class="form-label"><i class="fas fa-smile text-warning"></i> نقاط SmileOne</label>
                        <input type="number" step="0.01" value="0" class="form-control" id="smileone_points" name="smileone_points">
                    </div>
                    @endif

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> إضافة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body, h5, p {
        font-family: 'Arial', sans-serif;
    }

    .product-show {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    .table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .card-footer {
        background: #f8f9fa;
        text-align: right;
        border-top: 1px solid #e9ecef;
        padding: 10px 15px;
    }

    .btn-info, .btn-danger {
        margin-right: 5px;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover {
        opacity: 0.85;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Toggle Active Status
        $('.toggle-active').change(function() {
            var variantId = $(this).data('id');
            var isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("variants.toggleActive") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    variant_id: variantId,
                    is_active: isActive
                },
                success: function(response) {
                    if(response.success) {
                        alert(response.message);
                    } else {
                        alert('حدث خطأ أثناء تحديث الحالة.');
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء إرسال الطلب.');
                }
            });
        });

        // Save SmileOne Points
        $('.save-smileone-points').click(function() {
            var variantId = $(this).data('variant-id');
            var points = $(this).closest('.d-flex').find('.smileone-points-input').val();
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route("variants.updateSmileonePoints") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    variant_id: variantId,
                    smileone_points: points
                },
                success: function(response) {
                    if(response.success) {
                        btn.html('<i class="fas fa-check"></i>');
                        setTimeout(function() {
                            btn.html('<i class="fas fa-save"></i>');
                        }, 1500);
                    } else {
                        alert('حدث خطأ أثناء تحديث النقاط.');
                        btn.html('<i class="fas fa-save"></i>');
                    }
                    btn.prop('disabled', false);
                },
                error: function() {
                    alert('حدث خطأ أثناء إرسال الطلب.');
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i>');
                }
            });
        });

        // Quick Image Upload
        $('#quickImageUpload').change(function() {
            var file = this.files[0];
            if (!file) return;

            // التحقق من نوع الملف
            if (!file.type.match('image.*')) {
                alert('يرجى اختيار ملف صورة صالح');
                return;
            }

            // التحقق من حجم الملف (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('حجم الصورة يجب أن يكون أقل من 2 ميجابايت');
                return;
            }

            var formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            // إظهار مؤشر التحميل
            $('#imageUploadLoader').removeClass('d-none');

            $.ajax({
                url: '{{ route("products.updateImage", $product->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // تحديث الصورة مباشرة
                        if ($('#productImage').length) {
                            $('#productImage').attr('src', response.image_url + '?t=' + new Date().getTime());
                        } else {
                            $('#productImagePlaceholder').replaceWith(
                                '<img src="' + response.image_url + '" alt="Product Image" class="img-fluid rounded" id="productImage">'
                            );
                        }
                        alert('تم تحديث الصورة بنجاح');
                    } else {
                        alert(response.message || 'حدث خطأ أثناء تحديث الصورة');
                    }
                },
                error: function(xhr) {
                    var message = 'حدث خطأ أثناء رفع الصورة';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                },
                complete: function() {
                    $('#imageUploadLoader').addClass('d-none');
                    $('#quickImageUpload').val('');
                }
            });
        });
    });
</script>


@endsection
