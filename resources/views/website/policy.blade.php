@extends('layouts.web')

@section('title', 'سياسة الخصوصية')

@section('content')
<div class="container mt-5">
    <div class="row  p-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light p-3" style="background-color: #061f46!important;">  سياسة الخصوصية     </div>
                <div class="card-body">
                    @if ($content->policy)
                    {!! $content->policy !!}
                    @else 
                    <p class="text-center">لا توجد اي معلومات</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
