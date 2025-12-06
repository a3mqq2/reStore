@extends('layouts.web')

@section('title', 'سياسة الاسترجاع')

@section('content')
<div class="container mt-5">
    <div class="row  p-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light p-3" style="background-color: #061f46!important;">  سياسة الاسترجاع     </div>
                <div class="card-body">
                    @if ($content->returns)
                    {!! $content->returns !!}
                    @else 
                    <p class="text-center">لا توجد اي معلومات</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
