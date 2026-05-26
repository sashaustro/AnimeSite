<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnimeSite - {{ __('messages.catalog_title') }}</title>
</head>

<body style="background-color: #1a1a1d; color: white; font-family: sans-serif; padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
        <h1 style="margin: 0;">{{ __('messages.catalog_title') }}</h1>
        <a href="{{ route('anime.create') }}" style="background-color: #ff3366; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">{{ __('messages.add_anime') }}</a>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">

        @foreach($animes as $item)
            <div style="background-color: #252529; border-radius: 8px; width: 220px; padding: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">

                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 5px;">
                @else
                    <div style="width: 100%; height: 300px; background-color: #333; border-radius: 5px; display: flex; align-items: center; justify-content: center;">{{ __('messages.no_poster') }}</div>
                @endif

                <h3 style="margin: 10px 0 5px 0; font-size: 18px;">{{ $item->title }}</h3>
                <span style="background-color: #ff3366; padding: 2px 8px; border-radius: 12px; font-size: 12px;">{{ $item->genre }}</span>

                <p style="font-size: 14px; color: #aaa; margin-top: 10px;">
                    {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                </p>
                
                <a href="{{ route('anime.show', $item->id) }}" style="display: block; margin-top: 10px; margin-bottom: 15px; color: #ff3366; text-decoration: none; font-weight: bold;">{{ __('messages.details') }} &rarr;</a>
                
                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <a href="{{ route('anime.edit', $item->id) }}" style="background-color: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px;">Редагувати</a>

                    <form action="{{ route('anime.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Точно видалити це аніме?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; font-size: 14px; cursor: pointer;">Видалити</button>
                    </form>
                </div>
            </div>
        @endforeach

    </div>

    <div style="margin-top: 40px;">
        {{ $animes->links() }}
    </div>

</body>

</html>