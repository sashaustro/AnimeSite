<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Акаунт видалено</title>
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
            background: #ef4444; /* Red for deletion */
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
            <h2>Прощавай, {{ $userName }}!</h2>
            <p>Повідомляємо, що ваш акаунт на платформі AnimeHub було успішно видалено.</p>
            <p>Усі ваші персональні дані, списки та налаштування були стерті з нашої бази даних. Якщо ви вирішите повернутися, ми завжди будемо раді бачити вас знову!</p>
            
            <p style="margin-top: 30px;">З найкращими побажаннями,<br>Команда AnimeHub</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AnimeHub. Всі права захищені.
        </div>
    </div>
</body>
</html>
