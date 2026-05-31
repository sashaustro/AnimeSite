<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перегляд епізоду: {{ $episode->title ?? 'Епізод ' . $episode->episode_number }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #0f111a;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 9999;
            background: rgba(0,0,0,0.6);
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
            font-size: 14px;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="{{ route('anime.edit', $episode->anime_id) }}" class="back-btn">&larr; Назад до аніме</a>
    {!! $episode->video_url !!}
</body>
</html>
