<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('reports.index', compact('customers'));
    }

    public function ordersReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));
        $customerId = $request->input('customer_id');

        $orders = Order::query()
            ->whereBetween('order_date', [$fromDate, $toDate]);

        if ($customerId) {
            $orders->where('customer_id', $customerId);
        }


        return view('reports.orders', ['orders' => $orders->get()]);
    }

    public function topCustomersReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));

        $topCustomers = Order::selectRaw('customer_id, COUNT(*) as total_orders')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('customer_id')
            ->orderByDesc('total_orders')
            ->take(10)
            ->get();

        return view('reports.top-customers', ['topCustomers' => $topCustomers]);
    }

    public function topProductsReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));

        $topProducts = OrderProduct::selectRaw('product_id, COUNT(*) as total_sales')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();

        return view('reports.top-products', ['topProducts' => $topProducts]);
    }
    public function topCitiesReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));
    
        $topCities = Order::selectRaw('customers.city_id, cities.name as city_name, COUNT(*) as total_orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy('customers.city_id', 'cities.name')
            ->orderByDesc('total_orders')
            ->take(10)
            ->get();
    
        return view('reports.top-cities', ['topCities' => $topCities]);
    }
    

    public function usedCouponsReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));

        $usedCoupons = Order::selectRaw('coupon_id, COUNT(*) as total_uses')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->whereNotNull('coupon_id') // Exclude orders without coupons
            ->groupBy('coupon_id')
            ->orderByDesc('total_uses')
            ->take(10)
            ->get();

        return view('reports.used-coupons', ['usedCoupons' => $usedCoupons]);
    }

    /**
     * تقرير الأرباح - يحسب الربح من كل طلب (السعر - التكلفة)
     */
    public function profitReport(Request $request)
    {
        $fromDate = Carbon::parse($request->input('from_date'));
        $toDate = Carbon::parse($request->input('to_date'));
        $status = $request->input('status', 'approved'); // افتراضياً الطلبات المكتملة فقط

        $orders = Order::with(['orderProducts.product', 'customer'])
            ->whereBetween('order_date', [$fromDate, $toDate])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('order_date')
            ->get();

        $totalRevenue = 0; // إجمالي المبيعات
        $totalCost = 0; // إجمالي التكلفة
        $totalProfit = 0; // إجمالي الربح

        $ordersData = [];

        foreach ($orders as $order) {
            $orderRevenue = 0;
            $orderCost = 0;
            $orderProfit = 0;

            foreach ($order->orderProducts as $orderProduct) {
                $price = $orderProduct->price * $orderProduct->quantity;
                $cost = ($orderProduct->cost ?? 0) * $orderProduct->quantity;
                $profit = $price - $cost;

                $orderRevenue += $price;
                $orderCost += $cost;
                $orderProfit += $profit;
            }

            $ordersData[] = [
                'order' => $order,
                'revenue' => $orderRevenue,
                'cost' => $orderCost,
                'profit' => $orderProfit,
            ];

            $totalRevenue += $orderRevenue;
            $totalCost += $orderCost;
            $totalProfit += $orderProfit;
        }

        return view('reports.profit', [
            'ordersData' => $ordersData,
            'totalRevenue' => $totalRevenue,
            'totalCost' => $totalCost,
            'totalProfit' => $totalProfit,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'status' => $status,
        ]);
    }

}
