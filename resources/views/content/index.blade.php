@extends('layouts.app')

@section('title', 'تحديث محتوى الموقع')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-edit"></i> تحديث محتوى الموقع
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('content.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="facebook"><i class="fab fa-facebook"></i> Facebook</label>
                                    <input type="text" name="facebook" class="form-control" id="facebook" placeholder="Facebook" value="{{ $content->facebook ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="instagram"><i class="fab fa-instagram"></i> Instagram</label>
                                    <input type="text" name="instagram" class="form-control" id="instagram" placeholder="Instagram" value="{{ $content->instagram ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ $content->email ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control" id="whatsapp" placeholder="WhatsApp" value="{{ $content->whatsapp ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="telegram"><i class="fab fa-telegram"></i> Telegram</label>
                                    <input type="text" name="telegram" class="form-control" id="telegram" placeholder="مثال: restore_ly" value="{{ $content->telegram ?? '' }}">
                                    <small class="form-text text-muted">اسم المستخدم في تيليجرام بدون @</small>
                                </div>
                            </div>
                            <!-- New fields for Libyana and Al-Madar -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="libyana"><i class="fas fa-phone"></i> ليبيانا</label>
                                    <input type="text" name="libyana" class="form-control" id="libyana" placeholder="رقم ليبيانا" value="{{ $content->libyana ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="madar"><i class="fas fa-phone"></i> المدار</label>
                                    <input type="text" name="madar" class="form-control" id="madar" placeholder="رقم المدار" value="{{ $content->madar ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="vodafone_cash"><i class="fas fa-wallet text-danger"></i> فودافون كاش</label>
                                    <input type="text" name="vodafone_cash" class="form-control" id="vodafone_cash" placeholder="رقم فودافون كاش" value="{{ $content->vodafone_cash ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="asiacell"><i class="fas fa-phone text-warning"></i> آسياسيل</label>
                                    <input type="text" name="asiacell" class="form-control" id="asiacell" placeholder="رقم آسياسيل" value="{{ $content->asiacell ?? '' }}">
                                </div>
                            </div>
                            <!-- End new fields -->
                            <!-- Point Cost Section -->
                            <div class="col-md-12 mb-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0 text-white"><i class="fas fa-coins"></i> تكلفة النقطة حسب مزود الخدمة</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="point_cost_libyana"><i class="fas fa-mobile-alt text-danger"></i> ليبيانا (د.ل)</label>
                                                    <input type="number" step="0.0001" min="0" name="point_cost_libyana" class="form-control" id="point_cost_libyana" placeholder="0.01" value="{{ $content->point_cost_libyana ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="point_cost_almadar"><i class="fas fa-mobile-alt text-success"></i> المدار (د.ل)</label>
                                                    <input type="number" step="0.0001" min="0" name="point_cost_almadar" class="form-control" id="point_cost_almadar" placeholder="0.01" value="{{ $content->point_cost_almadar ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="point_cost_red"><i class="fas fa-mobile-alt text-primary"></i> اسياسيل (د)</label>
                                                    <input type="number" step="0.0001" min="0" name="point_cost_red" class="form-control" id="point_cost_red" placeholder="0.01" value="{{ $content->point_cost_red ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="point_cost_vfcash"><i class="fas fa-wallet text-danger"></i> فودافون كاش (ج.م)</label>
                                                    <input type="number" step="0.0001" min="0" name="point_cost_vfcash" class="form-control" id="point_cost_vfcash" placeholder="0.01" value="{{ $content->point_cost_vfcash ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="point_cost"><i class="fas fa-coins text-secondary"></i> افتراضي</label>
                                                    <input type="number" step="0.0001" min="0" name="point_cost" class="form-control" id="point_cost" placeholder="0.01" value="{{ $content->point_cost ?? '0.01' }}">
                                                    <small class="form-text text-muted">يستخدم إذا لم يتم تحديد قيمة للمزود</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Point Cost Section -->

                            <!-- Currency Exchange Section -->
                            <div class="col-md-12 mb-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i> أسعار الصرف</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="dollar_buy_rate"><i class="fas fa-arrow-down text-success"></i> سعر شراء الدولار (د.ل)</label>
                                                    <input type="number" step="0.01" min="0" name="dollar_buy_rate" class="form-control" id="dollar_buy_rate" placeholder="4.85" value="{{ $content->dollar_buy_rate ?? '' }}">
                                                    <small class="form-text text-muted">سعر شراء الدولار الواحد بالدينار الليبي</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="dollar_sell_rate"><i class="fas fa-arrow-up text-danger"></i> سعر بيع الدولار (د.ل)</label>
                                                    <input type="number" step="0.01" min="0" name="dollar_sell_rate" class="form-control" id="dollar_sell_rate" placeholder="4.90" value="{{ $content->dollar_sell_rate ?? '' }}">
                                                    <small class="form-text text-muted">سعر بيع الدولار الواحد بالدينار الليبي</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="smileone_point_usd"><i class="fas fa-smile text-warning"></i> سعر نقطة SmileOne ($)</label>
                                                    <input type="number" step="0.0001" min="0" name="smileone_point_usd" class="form-control" id="smileone_point_usd" placeholder="0.0100" value="{{ $content->smileone_point_usd ?? '' }}">
                                                    <small class="form-text text-muted">سعر نقطة سمايل ون الواحدة بالدولار</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Currency Exchange Section -->

                            <!-- Maintenance Mode Section -->
                            <div class="col-md-12 mb-4">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0 text-white"><i class="fas fa-tools"></i> وضع الصيانة</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ ($content->maintenance_mode ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="maintenance_mode">
                                                        <strong>تفعيل وضع الصيانة</strong>
                                                        <small class="d-block text-muted">عند التفعيل، سيتم عرض صفحة الصيانة لجميع الزوار (ماعدا المسؤولين)</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="maintenance_message"><i class="fas fa-comment-alt"></i> رسالة الصيانة</label>
                                                    <textarea name="maintenance_message" class="form-control" id="maintenance_message" rows="3" placeholder="الموقع تحت الصيانة حالياً، نعتذر عن الإزعاج...">{{ $content->maintenance_message ?? 'الموقع تحت الصيانة حالياً. سنعود قريباً!' }}</textarea>
                                                    <small class="form-text text-muted">الرسالة التي ستظهر للزوار أثناء الصيانة</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Maintenance Mode Section -->

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="policy"><i class="fas fa-shield-alt"></i> سياسة الخصوصية</label>
                                    <textarea name="policy" class="form-control ckeditor" id="policy" placeholder="سياسة الخصوصية">{{ $content->policy ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="returns"><i class="fas fa-undo"></i> سياسة الإرجاع</label>
                                    <textarea name="returns" class="form-control ckeditor" id="returns" placeholder="سياسة الإرجاع">{{ $content->returns ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="about"><i class="fas fa-info-circle"></i> عن الموقع</label>
                                    <textarea name="about" class="form-control ckeditor" id="about" placeholder="عن الموقع">{{ $content->about ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="message"><i class="fas fa-bullhorn"></i> رسالة الإعلان</label>
                                    <textarea name="message" class="form-control ckeditor" id="message" placeholder="رسالة الإعلان">{{ $content->message ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3"><i class="fas fa-save"></i> تحديث</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Banners Section -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-images"></i> إدارة البنرات
                </div>
                <div class="card-body">
                    @if (session('banner_success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('banner_success') }}
                        </div>
                    @endif
                    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="banner_image"><i class="fas fa-image"></i> رفع بنر</label>
                            <input type="file" name="banner_image" class="form-control" id="banner_image" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="banner_link"><i class="fas fa-link"></i> رابط البنر</label>
                            <input type="url" name="banner_link" class="form-control" id="banner_link" placeholder="https://example.com">
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> رفع</button>
                    </form>
                    

                    <hr>

                    <h5 class="card-title mt-4"><i class="fas fa-list"></i> البنرات الحالية</h5>
                    @if($banners->isEmpty())
                        <p class="text-muted">لا توجد بنرات حاليا.</p>
                    @else
                        <div class="row">
                            @foreach($banners as $banner)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $banner->image) }}" class="card-img-top" alt="Banner Image">
                                        <div class="card-body text-center">
                                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">
                                                    <i class="fas fa-trash-alt"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.24.0/standard/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/4.24.0/standard-all/translations/ar.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('policy');
            CKEDITOR.replace('returns');
            CKEDITOR.replace('about');
            CKEDITOR.replace('message');
        } else {
            console.error("CKEditor is not defined");
        }
    });
</script>
@endsection
