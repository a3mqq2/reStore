<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تنبيه: قيمة تحويل غير صحيحة</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            border-radius: 8px;
        }
        .header {
            background-color: #d9534f;
            color: #ffffff;
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777777;
            text-align: center;
            margin-top: 20px;
        }
        .details {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
        }
        .details p {
            margin: 5px 0;
        }
        .alert {
            color: #d9534f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>تنبيه: قيمة تحويل غير صحيحة من زبون</h2>
        </div>
        <div class="content">
            <p>مرحباً،</p>
            <p>تم استقبال قيمة تحويل غير صحيحة من قبل أحد الزبائن.</p>
            <div class="details">
                <p><strong>اسم الزبون:</strong> {{ $customerName }}</p>
                <p><strong>رقم الهاتف:</strong> {{ $customerPhone }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $customerEmail }}</p>
                <p class="alert"><strong>القيمة المستلمة:</strong> {{ $invalidValue }} دينار</p>
            </div>
            <p>يرجى التواصل مع الزبون لحل هذه المشكلة.</p>
        </div>
        <div class="footer">
            <p>هذا البريد مرسل تلقائياً من نظامك.</p>
        </div>
    </div>
</body>
</html>
