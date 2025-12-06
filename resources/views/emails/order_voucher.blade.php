{{-- resources/views/emails/order_voucher.blade.php --}}
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل القسائم | طلب #{{ $orderId }}</title>
    <style>
        body{font-family:'Arial',sans-serif;direction:rtl;text-align:right;background:#f7f7f7;margin:0;padding:0}
        .container{background:#fff;width:80%;margin:20px auto;border-radius:8px;padding:0 0 20px}
        .header{background:#28a745;color:#fff;padding:10px 20px;border-top-left-radius:8px;border-top-right-radius:8px}
        .content{padding:20px}
        .footer{font-size:12px;color:#777;text-align:center;margin-top:20px}
        .details{background:#f1f1f1;padding:15px;border-radius:5px}
        .details p{margin:5px 0}
        ul.codes{background:#fafafa;border:1px solid #ddd;border-radius:5px;padding:10px;list-style:decimal;margin:10px 0}
        ul.codes li{direction:ltr;font-weight:bold}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>تفاصيل القسائم لطلبك رقم {{ $orderId }}</h2>
        </div>
        <div class="content">
            <p>مرحباً {{ $customerName }}،</p>
            <p>شكراً لثقتك بنا. فيما يلي تفاصيل عملية الشراء:</p>

            <div class="details">
                <p><strong>المنتج:</strong> {{ $productName }}</p>
                <p><strong>الكمية:</strong> {{ $quantity }}</p>
                <p><strong>السعر لكل وحدة:</strong> {{ $price }}</p>
                <p><strong>الإجمالي:</strong> {{ $total }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $orderDate }}</p>
            </div>

            <h3>أكواد / قسائم الشراء:</h3>
            <ul class="codes">
                @foreach($vouchers as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>

            <p>استخدم الأكواد أعلاه حسب التعليمات في اللعبة أو المنصة ذات الصلة.</p>
            <p>إذا واجهت أي مشكلة، لا تتردد في التواصل معنا.</p>
        </div>
        <div class="footer">
            <p>تم إرسال هذا البريد تلقائياً بواسطة نظامنا.</p>
        </div>
    </div>
</body>
</html>
