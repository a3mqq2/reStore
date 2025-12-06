@extends('layouts.app')

@section('title', 'تعديل منتج')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">تعديل منتج</div>
                <div class="card-body">
                    <product-form :product="{{ $product->id }}" img="{{Storage::url($product->image)}}" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
