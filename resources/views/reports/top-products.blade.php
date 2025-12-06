@extends('layouts.app')

@section('title', 'تقرير أكثر المنتجات مبيعاً')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header bg-primary text-light">
            تقرير أكثر المنتجات مبيعاً خلال فترة
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>إجمالي المبيعات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                        <tr>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->total_sales }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
