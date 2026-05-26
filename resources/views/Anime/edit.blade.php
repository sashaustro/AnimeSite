<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати Аніме</title>
</head>
<body style="background-color: #1a1a1d; color: white; font-family: sans-serif; padding: 20px;">
    
    <h2>Редагувати: {{ $anime->title }}</h2>

    <form action="{{ route('anime.update', $anime->id) }}" method="POST" enctype="multipart/form-data" style="max-width: 500px; display: flex; flex-direction: column; gap: 15px;">
        @csrf
        @method('PUT') <div>
            <label>Назва аніме:</label><br>
            <input type="text" name="title" value="{{ $anime->title }}" required style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;">
        </div>

        <div>
            <label>Жанр / Категорія:</label><br>
            <input type="text" name="genre" value="{{ $anime->genre }}" required style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;">
        </div>

        <div>
            <label>Опис:</label><br>
            <textarea name="description" rows="4" style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;">{{ $anime->description }}</textarea>
        </div>

        <div>
            <label>Постер (Зображення):</label><br>
            @if($anime->image)
                <img src="{{ asset('storage/' . $anime->image) }}" width="100" style="margin-bottom: 10px; border-radius: 5px;"><br>
            @endif
            <input type="file" name="image" accept="image/*" style="margin-top: 5px;">
        </div>

        <button type="submit" style="padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-weight: bold;">Зберегти зміни</button>
    </form>

</body>
</html>