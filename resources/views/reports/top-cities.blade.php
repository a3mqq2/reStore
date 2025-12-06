@extends('layouts.app')

@section('title', 'تقرير أكثر المدن شراءً')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header bg-primary text-light">
            تقرير أكثر المدن شراءً خلال فترة
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>المدينة</th>
                        <th>إجمالي الطلبات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCities as $city)
                        <tr>
                            <td>{{ $city->city_name }}</td>
                            <td>{{ $city->total_orders }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
