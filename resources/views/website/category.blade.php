@extends('layouts.web')

@section('title', 'عرض القسم')

@section('content')
<div class="axil-main-slider-area ">
    <div class="container">
        <div class="row align-items-end p-1">
               @isset($ProductCategory)
                   <h3>{{$ProductCategory->name}}</h3>
               @endisset
        </div>
        <div class="container">
            <div class="row">
                @if (count($products))
                @foreach ($products as $product)
                <div class="col-xl-4 col-md-6 col-sm-6 col-6">
                    <div class="slick-single-layout">
                        <div class="axil-product product-style-three">
                            <div class="thumbnail position-relative">
                                <a  href="{{route('website.show-product', $product)}}">
                                    <img  src="{{ Storage::url($product->image) }}" alt="Product Images">
                                </a>
                                @if ($product->discount && $product->discount->discount_percentage)
                                <div class="discount-badge">
                                    -{{ $product->discount->discount_percentage }}%
                                </div>
                                @endif
                            </div>
                            <div class="product-content">
                                <div class="inner">
                                    <h5 class="title text-right" style="text-align: right !important; margin-bottom: 5px;">
                                        <a href="{{route('website.show-product', $product)}}">{{ $product->name }}</a>
                                    </h5>
                                    <div class="product-price-variant text-right" style="margin-top: 0;">
                                        <span class="price current-price text-right">
                                            @php
                                                // Get the minimum price from active variants using calculated_price
                                                // This will automatically use SmileOne or MooGold based on availability
                                                $theLessPrice = $product->variants
                                                    ->where('is_active', 1)
                                                    ->filter(function($variant) {
                                                        return $variant->calculated_price != null;
                                                    })
                                                    ->min('calculated_price');

                                                $discountedPrice = $theLessPrice;
                                                if ($theLessPrice && $product->discount && $product->discount->discount_percentage) {
                                                    $discount = $theLessPrice * ($product->discount->discount_percentage / 100);
                                                    $discountedPrice = $theLessPrice - $discount;
                                                }
                                            @endphp

                                            <h6 class="m-0 font-weight-bold" style="text-align: right !important; font-weight: bold !important; margin-top: 5px;">
                                                السعر يبدا من :
                                                @if ($discountedPrice && $discountedPrice != $theLessPrice)
                                                    <del>{{ $theLessPrice }} نقطة</del>
                                                    {{ $discountedPrice }} نقطة
                                                @elseif($theLessPrice)
                                                    {{ $theLessPrice }} نقطة
                                                @else
                                                    غير متوفر
                                                @endif
                                            </h6>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else 
                <p class="text-center">لا توجد اي بيانات في الوقت الحالي</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    /* Discount Badge styles */
    .discount-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: #ff4d4d;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 1rem;
      font-weight: bold;
    }
    </style>
    
@endsection