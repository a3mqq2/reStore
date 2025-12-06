<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');
        
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        .email-header {
            background-color: #061f46;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .email-header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
        }
        .email-body p {
            margin: 0 0 15px;
            font-size: 16px;
        }
        .email-body ul {
            list-style: none;
            padding: 0;
            margin: 0 0 15px;
        }
        .email-body ul li {
            background: #f7f7f7;
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 5px;
        }
        .email-footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            font-size: 14px;
            color: #555555;
        }
    </style>
    <title>طلب جديد</title>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/images/re-store-v.png') }}" alt="Logo">
            <h1>طلب جديد</h1>
        </div>
        <div class="email-body">
            <p>تم تقديم طلب جديد من قبل {{ $order->customer->name }}.</p>
            <p>تفاصيل الطلب:</p>
            <ul>
                <li>رقم الطلب: {{ $order->id }}</li>
                <li>التاريخ: {{ $order->order_date }}</li>
                <li>طريقة الدفع: {{ $order->paymentMethod->name }}</li>
                <li>الإجمالي: {{ $order->discounted_total }} {{ $order->currency }}</li>
            </ul>
            <p>المنتجات المطلوبة:</p>
            <ul>
                @foreach ($order->products as $product)
                    <li>{{ $product->name }} - الكمية: {{ $product->quantity }} - السعر: {{ $product->price }}</li>
                @endforeach
            </ul>
        </div>
        <div class="email-footer">
            <p>شكراً لاستخدامك موقعنا.</p>
        </div>
    </div>
</body>
</html>
