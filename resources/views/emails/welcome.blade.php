<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Вітаємо на AnimeHub!</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f111a;
            color: #ffffff;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1c1e2d;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(90deg, #8b5cf6, #d946ef);
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
            color: #e2e8f0;
            line-height: 1.6;
        }
        .content h2 {
            color: #ffffff;
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            background-color: #8b5cf6;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            width: 250px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #7c3aed;
        }
        .footer {
            background-color: #0f111a;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AnimeHub</h1>
        </div>
        <div class="content">
            <h2>Вітаємо, {{ $user->username ?? $user->name }}!</h2>
            <p>Дякуємо за реєстрацію на AnimeHub — твоєму найкращому порталі для перегляду аніме. Ми дуже раді бачити тебе в нашій спільноті!</p>
            <p>Тут ти зможеш знаходити нові тайтли, складати власні списки улюблених аніме, ділитися враженнями та оцінками.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="btn" style="display: inline-block; background-color: #8b5cf6; color: #ffffff !important; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: bold; font-size: 16px; width: 250px; text-align: center; margin-top: 20px;">Перейти на сайт</a>
            </div>
            
            <p style="margin-top: 30px;">З повагою,<br>Команда AnimeHub</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AnimeHub. Всі права захищені.
        </div>
    </div>
</body>
</html>
