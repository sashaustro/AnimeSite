<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Видалення акаунта</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #1a1a24; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #ef4444; padding: 30px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold; letter-spacing: 1px;">AnimeHub</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; color: #e2e8f0;">
                            <h2 style="color: #ffffff; font-size: 22px; margin-top: 0; margin-bottom: 20px;">Прощавай, {{ $userName }}!</h2>
                            
                            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                Твій акаунт на платформі AnimeHub було заплановано до видалення.
                            </p>
                            
                            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px; color: #fca5a5;">
                                Усі твої персональні дані, списки та налаштування будуть остаточно стерті з нашої бази даних через <strong>7 днів</strong>. Цю дію неможливо буде скасувати після завершення терміну.
                            </p>

                            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
                                Якщо ти передумав або видалив акаунт випадково, ти можеш скасувати видалення просто авторизувавшись на сайті знову.
                            </p>

                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login') }}" style="display: inline-block; padding: 14px 40px; background-color: #8b5cf6; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; width: 250px; text-align: center;">Відновити акаунт</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 16px; line-height: 1.6; margin-top: 40px; margin-bottom: 5px;">
                                З найкращими побажаннями,
                            </p>
                            <p style="font-size: 16px; line-height: 1.6; margin-top: 0; font-weight: bold; color: #ffffff;">
                                Команда AnimeHub
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #111118; padding: 20px; color: #94a3b8; font-size: 14px;">
                            &copy; {{ date('Y') }} AnimeHub. Всі права захищені.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
