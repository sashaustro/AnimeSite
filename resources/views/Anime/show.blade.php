<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>{{ $anime->title }}</title>
</head>
<body style="background-color: #1a1a1d; color: white; font-family: sans-serif; padding: 20px;">

    <a href="{{ route('anime.index') }}" style="color: #ff3366; text-decoration: none;">{{ __('messages.back') }}</a>

    <div style="display: flex; gap: 20px; margin-top: 20px; background-color: #252529; padding: 20px; border-radius: 8px;">
        @if($anime->image)
            <img src="{{ asset('storage/' . $anime->image) }}" style="width: 300px; border-radius: 5px;">
        @endif
        
        <div>
            <h1>{{ $anime->title }}</h1>
            <span style="background-color: #ff3366; padding: 5px 10px; border-radius: 12px; font-weight: bold;">{{ $anime->genre }}</span>
            <p style="margin-top: 20px; font-size: 16px; line-height: 1.5; color: #ccc;">{{ $anime->description }}</p>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <h2>{{ __('messages.reviews_title') }}</h2>

        <form action="{{ route('reviews.store', $anime->id) }}" method="POST" style="background-color: #333; padding: 15px; border-radius: 8px; margin-bottom: 20px; max-width: 600px;">
            @csrf
            <div>
                <label>{{ __('messages.your_rating') }}</label>
                <input type="number" name="rating" min="1" max="10" required style="margin-left: 10px; padding: 5px; width: 60px;">
            </div>
            <div style="margin-top: 10px;">
                <textarea name="comment" placeholder="{{ __('messages.placeholder') }}" rows="3" style="width: 100%; padding: 8px; background: #222; color: white; border: 1px solid #555;"></textarea>
            </div>
            <button type="submit" style="margin-top: 10px; padding: 8px 15px; background-color: #ff3366; color: white; border: none; cursor: pointer; border-radius: 5px;">{{ __('messages.submit_btn') }}</button>
        </form>

        @foreach($anime->reviews as $review)
            <div style="background-color: #252529; padding: 15px; border-radius: 8px; margin-bottom: 10px; max-width: 600px;">
                <div style="display: flex; justify-content: space-between;">
                    <strong>{{ $review->user->name }}</strong>
                    <span style="color: #ffcc00; font-weight: bold;">★ {{ $review->rating }}/10</span>
                </div>
                <p style="margin: 10px 0 0 0; color: #aaa;">{{ $review->comment }}</p>
                <small style="color: #666;">{{ $review->created_at->format('d.m.Y H:i') }}</small>
            </div>
        @endforeach
    </div>

</body>
</html>