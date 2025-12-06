<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموقع تحت الصيانة - ReStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            direction: rtl;
        }

        .maintenance-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .maintenance-icon {
            font-size: 120px;
            color: #667eea;
            margin-bottom: 30px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .maintenance-container h1 {
            font-size: 36px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .maintenance-container p {
            font-size: 18px;
            color: #718096;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .maintenance-message {
            background: #f7fafc;
            border-right: 4px solid #667eea;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            font-size: 16px;
            color: #4a5568;
        }

        .social-links {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #764ba2;
            transform: translateY(-5px);
        }

        .logo {
            max-width: 150px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 20px;
            }

            .maintenance-container h1 {
                font-size: 28px;
            }

            .maintenance-icon {
                font-size: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        @if(file_exists(public_path('assets/images/re-store-v.png')))
            <img src="{{ asset('assets/images/re-store-v.png') }}" alt="ReStore Logo" class="logo">
        @endif

        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>

        <h1>الموقع تحت الصيانة</h1>

        <p>نعمل حالياً على تحسين الموقع لتقديم أفضل تجربة لك</p>

        @if(isset($message) && $message)
            <div class="maintenance-message">
                <i class="fas fa-info-circle"></i> {{ $message }}
            </div>
        @endif

        <p>نعتذر عن الإزعاج، وسنعود قريباً بإذن الله</p>

        @php
            $content = \App\Models\Content::first();
        @endphp

        @if($content)
            <div class="social-links">
                @if($content->facebook)
                    <a href="{{ $content->facebook }}" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif

                @if($content->instagram)
                    <a href="{{ $content->instagram }}" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif

                @if($content->whatsapp)
                    <a href="https://wa.me/{{ $content->whatsapp }}" target="_blank" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif

                @if($content->email)
                    <a href="mailto:{{ $content->email }}" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
