@extends('layouts.app')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">إضافة منتج جديد</div>
                <div class="card-body">
                    <product-form/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
