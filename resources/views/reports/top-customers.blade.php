@extends('layouts.app')

@section('title', 'تقرير أكثر الزبائن طلباً')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header bg-primary text-light">
            تقرير أكثر الزبائن طلباً خلال فترة
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>اسم الزبون</th>
                        <th>إجمالي الطلبات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCustomers as $customer)
                        <tr>
                            <td>{{ $customer->customer->name }}</td>
                            <td>{{ $customer->total_orders }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
