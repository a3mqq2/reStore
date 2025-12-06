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
                                                $FirstVariant = $product->variants->first();
                                                $LastVariant = $product->variants->sortByDesc('id')->first();
                                                $firstGetPrice = $FirstVariant->prices->where('payment_method_id', $currentPaymentMethod)->first();
                                                $lastGetPrice = $LastVariant->prices->where('payment_method_id', $currentPaymentMethod)->first();
                                                $paymentMethod = App\Models\PaymentMethod::find($currentPaymentMethod);
                                                $discountedPrice = $firstGetPrice->price;
                                                if ($product->discount && $product->discount->discount_percentage) {
                                                    $discount = $firstGetPrice->price * ($product->discount->discount_percentage / 100);
                                                    $discountedPrice = $firstGetPrice->price - $discount;
                                                }
                                            @endphp

                                            <h6 class="m-0 font-weight-bold" style="text-align: right !important; font-weight: bold !important; margin-top: 5px;">
                                                السعر يبدا من : 
                                                @if ($discountedPrice != $firstGetPrice->price)
                                                    <del>{{ $firstGetPrice->price }} {{ $paymentMethod->currency->symbol }}</del>
                                                    {{ $discountedPrice }} {{ $paymentMethod->currency->symbol }}
                                                @else
                                                    {{ $firstGetPrice->price }} {{ $paymentMethod->currency->symbol }}
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