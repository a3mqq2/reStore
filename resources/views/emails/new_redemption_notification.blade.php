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
        .redemption-details {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>استرداد جديد</h1>
        </div>
        <div class="email-body">
            <p>مرحباً،</p>
            <p>تم إنشاء استرداد جديد من العميل <strong>{{ $customerName }}</strong>.</p>
            <div class="redemption-details">
                <p>اسم المنتج: {{ $productName }}</p>
                <p>قيمة الاسترداد: {{ $cashbackAmount }} نقطة</p>
                <p>ملاحظات: {{ $notes ? $notes : 'لا توجد ملاحظات' }}</p>
            </div>
            <p>يرجى مراجعة تفاصيل الاسترداد.</p>
        </div>
        <div class="email-footer">
            <p>شكراً لاستخدامك موقعنا.</p>
        </div>
    </div>
</body>
</html>
