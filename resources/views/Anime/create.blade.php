<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати Аніме</title>
</head>
<body style="background-color: #1a1a1d; color: white; font-family: sans-serif; padding: 20px;">
    
    <h2>Додати нове аніме в каталог</h2>

    <form action="{{ route('anime.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 500px; display: flex; flex-direction: column; gap: 15px;">
        
        @csrf 

        <div>
            <label>Назва аніме:</label><br>
            <input type="text" name="title" required style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;">
        </div>

        <div>
            <label>Жанр / Категорія:</label><br>
            <input type="text" name="genre" required style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;">
        </div>

        <div>
            <label>Опис:</label><br>
            <textarea name="description" rows="4" style="width: 100%; padding: 8px; margin-top: 5px; background: #333; color: white; border: 1px solid #555;"></textarea>
        </div>

        <div>
            <label>Постер (Зображення):</label><br>
            <input type="file" name="image" accept="image/*" style="margin-top: 5px;">
        </div>

        <button type="submit" style="padding: 10px; background-color: #ff3366; color: white; border: none; cursor: pointer; font-weight: bold;">Зберегти Аніме</button>
    </form>

</body>
</html>