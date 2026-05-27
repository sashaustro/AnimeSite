@extends('layouts.public')

@section('title', 'Мої колекції - AniHub')

@section('content')
<div class="container">
    <div style="display: flex; align-items: center; margin-bottom: 2rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Мої колекції</h2>
        <a href="{{ route('cabinet') }}" class="btn btn-outline" style="margin-left: auto;">В кабінет</a>
    </div>

    @if(session('success'))
        <div style="background: #2ecc71; color: #fff; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-bottom: 2rem;">
        <h3 style="margin-top: 0; margin-bottom: 1rem;">Створити нову колекцію</h3>
        <form action="{{ route('collections.store') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <input type="text" name="name" class="form-control" placeholder="Назва колекції..." required>
                </div>
                <div style="flex: 2; min-width: 250px;">
                    <input type="text" name="description" class="form-control" placeholder="Короткий опис (необов'язково)">
                </div>
                <div>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0; cursor: pointer;">
                        <input type="checkbox" name="is_public" value="1" checked> Публічна
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem;"><i class="fas fa-plus"></i> Створити</button>
            </div>
        </form>
    </div>

    @if($collections->count() > 0)
        <div style="display: grid; gap: 1.5rem;">
            @foreach($collections as $collection)
                <div style="background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden;">
                    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h3 style="margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                {{ $collection->name }}
                                @if(!$collection->is_public)
                                    <span style="font-size: 0.7rem; background: #e74c3c; color: white; padding: 2px 6px; border-radius: 10px;">Приватна</span>
                                @else
                                    <span style="font-size: 0.7rem; background: #3498db; color: white; padding: 2px 6px; border-radius: 10px;">Публічна</span>
                                @endif
                            </h3>
                            @if($collection->description)
                                <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">{{ $collection->description }}</p>
                            @endif
                        </div>
                        
                        <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цю колекцію? Всі аніме в ній залишаться на сайті.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="color: #e74c3c; border-color: #e74c3c; padding: 0.4rem 1rem;">
                                <i class="fas fa-trash"></i> Видалити
                            </button>
                        </form>
                    </div>
                    
                    <div style="padding: 1.5rem; background: var(--bg-dark);">
                        @if($collection->animes->count() > 0)
                            <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
                                @foreach($collection->animes as $anime)
                                    <a href="{{ route('anime.show', $anime->id) }}" style="display: block; width: 120px; flex-shrink: 0;">
                                        @if($anime->image)
                                            <img src="{{ asset('storage/' . $anime->image) }}" alt="poster" style="width: 100%; aspect-ratio: 2/3; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 0.5rem;">
                                        @else
                                            <div style="width: 100%; aspect-ratio: 2/3; background: #333; display: flex; align-items: center; justify-content: center; color: #666; border-radius: var(--radius-md); margin-bottom: 0.5rem;">Немає</div>
                                        @endif
                                        <div style="font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center;">{{ $anime->title }}</div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">В цій колекції ще немає аніме. Відкрийте будь-яке аніме та натисніть "В колекцію".</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-muted);">У вас ще немає колекцій</h3>
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Створіть свою першу колекцію вище, щоб зручно групувати аніме.</p>
        </div>
    @endif
</div>
@endsection
