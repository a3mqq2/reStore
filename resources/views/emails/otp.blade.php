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
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
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
            <img src="{{ asset('assets/images/re-store-v.png') }}" alt="Logo">
            <h1>رمز التحقق الخاص بك</h1>
        </div>
        <div class="email-body">
            <p>استخدم رمز التحقق التالي لإكمال عملية التسجيل:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذا البريد الإلكتروني.</p>
        </div>
        <div class="email-footer">
            <p>شكراً لاستخدامك موقعنا.</p>
        </div>
    </div>
</body>
</html>
