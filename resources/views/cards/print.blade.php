<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة بطاقة #{{ $card->id }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
        }
        .card-details {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .secret-number.show {
            filter: none;
        }
    </style>
</head>
<body>
    <div class="card-details">
        <h3 class="text-center">بطاقة #{{ $card->id }}</h3>
        <p><strong>الرقم السري:</strong> <span class="secret-number" id="secret-number">{{ $card->secret_number }}</span></p>
        <p><strong>الرقم التسلسلي:</strong> {{ $card->serial_number }}</p>
        <p><strong>المبلغ:</strong> {{ $card->amount }} {{ $card->currency_symbol ?? 'د.ل' }}</p>
        @if($card->customer)
            <p><strong>الزبون:</strong> {{ $card->customer->name }}</p>
        @endif
        <p><strong>الحالة:</strong> 
            @if($card->status == 'new')
                جديد
            @elseif($card->status == 'used')
                مستعمل
            @endif
        </p>
        <button class="btn btn-primary" onclick="window.print()">طباعة</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#secret-number').click(function(){
                $(this).toggleClass('show');
            });
        });
    </script>
</body>
</html>
