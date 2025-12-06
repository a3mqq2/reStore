<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #061f46;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .email-header img {
            max-width: 150px;
            margin: 0 auto;
        }
        .email-body {
            padding: 20px;
        }
        .email-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 12px;
            color: #555555;
        }
        .order-details {
            margin: 20px 0;
        }
        .order-details h3 {
            margin: 0 0 10px;
        }
        .order-details p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #061f46;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #163150;
        }
        .payment-code-box {
            margin: 20px 0;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 8px;
            text-align: center;
        }
        .payment-code-box p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/images/re-store-v.png') }}" alt="Logo">
            <h1>شكراََ لشرائك من خدماتنا</h1>
        </div>
        <div class="email-body">
            <p>مرحبا {{ $order->customer->name }}،</p>
            <p>{{ $messageText }}</p>
            <div class="order-details">
                <h3>تفاصيل الطلب:</h3>
                <p><strong>رقم الطلب:</strong> {{ $order->id }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $order->order_date }}</p>
                <p><strong>حالة الطلب:</strong> {{ $getArabicStatus($order->status) }}</p>
                <p><strong>الإجمالي:</strong> {{ $order->total_amount }} {{ $order->paymentMethod->currency->symbol }}</p>
                <p><strong>الإجمالي بعد الخصم:</strong> {{ number_format($order->discounted_total, 2) }} {{ $order->paymentMethod->currency->symbol }}</p>
            </div>
            <div class="payment-code-box">
                <p>{{ $order->payment_code }}</p>
            </div>
            {{-- <a href="{{ url('/orders/' . $order->id) }}" class="button">عرض الطلب</a> --}}
        </div>
        <div class="email-footer">
            <p>شكراً لشرائك من موقعنا.</p>
        </div>
    </div>
</body>
</html>
