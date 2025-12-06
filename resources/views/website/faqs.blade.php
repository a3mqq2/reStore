@extends('layouts.web')

@section('title', 'الاسئلة الشائعة')

@section('content')
<div class="axil-faq-section bg-color-white  pb--50 pb_sm--30">
    <div class="container">
        <div class="section-title-wrapper mb-5">
            <h2 class="display-4 font-weight-bold" style="font-weight: bold"><i class="fas fa-question-circle me-2"></i> الأسئلة الشائعة</h2>
            <p class="text-muted fs-5">إليك بعض الأسئلة الشائعة التي قد تهمك</p>
        </div>
        <div class="accordion" id="faqAccordion">
            @foreach ($faqs as $index => $faq)
                <div class="accordion-item mb-4">
                    <h2 class="accordion-header" id="heading{{ $index }}">
                        <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                            <i class="fas fa-chevron-down me-2"></i> <strong>{{ $faq->question }}</strong>
                        </button>
                    </h2>
                    <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body fs-5">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
