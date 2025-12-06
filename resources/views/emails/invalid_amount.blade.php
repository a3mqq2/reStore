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
            background-color: #d9534f;
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
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #d9534f;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>تنبيه: قيمة التحويل غير صحيحة</h1>
        </div>
        <div class="email-body">
            <p>مرحباً، <strong>{{ $customerName }}</strong></p>
            <p>لقد تلقينا قيمة تحويل غير صحيحة في رسالتك الأخيرة.</p>
            <div class="alert">
                <p><strong>القيمة المستلمة:</strong> {{ $invalidValue }} دينار</p>
                <p>هذه القيمة غير معتمدة ولا يمكن إضافتها إلى رصيد محفظتك.</p>
            </div>
            <p>للمزيد من المعلومات أو لحل هذه المشكلة، يرجى التواصل معنا عبر أحد الوسائل التالية:</p>
            <ul>
                <li>الهاتف: 0910747872</li>
                <li>الهاتف: 0946191846</li>
            </ul>
            <a href="{{ url('/contact') }}" class="btn">تواصل معنا</a>
        </div>
        <div class="email-footer">
            <p>شكراً لاستخدامك خدماتنا.</p>
        </div>
    </div>
</body>
</html>
