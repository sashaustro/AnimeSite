@extends('layouts.public')

@section('title', 'Жанри Аніме | Anime Portal CMS')

@section('content')
<div class="container">
    <h2 class="section-title">Каталог за жанрами</h2>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Тут будуть представлені жанри. Поки що ви можете переглянути повний каталог.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
        @php
            $dummyGenres = ['Екшн', 'Комедія', 'Драма', 'Фентезі', 'Романтика', 'Сьонен', 'Повсякденність', 'Детектив', 'Фантастика', 'Пригоди'];
        @endphp
        
        @foreach($dummyGenres as $genre)
            <a href="#" style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 1.5rem 1rem; border-radius: var(--radius-md); text-align: center; color: var(--text-primary); text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--accent-color)'; this.style.color='var(--accent-color)';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)';">
                {{ $genre }}
            </a>
        @endforeach
    </div>

    <h2 class="section-title">Всі аніме</h2>
    <div class="anime-grid">
        @forelse ($animes as $anime)
            <a href="{{ route('anime.show', $anime->id) }}" class="anime-card">
                @if($anime->image)
                    <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="anime-poster">
                @else
                    <div class="anime-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
                @endif
                <div class="anime-info">
                    <h3 class="anime-title" title="{{ $anime->title }}">{{ $anime->title }}</h3>
                    <div class="anime-meta">
                        <span>
                            @if(isset($anime->genres) && $anime->genres->count() > 0)
                                {{ $anime->genres->first()->name }}
                            @else
                                Аніме
                            @endif
                        </span>
                        <span>{{ $anime->year ?? '?' }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: var(--text-muted);">
                <h2>Каталог порожній...</h2>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 3rem;">
        {{ $animes->links() }}
    </div>
</div>
@endsection
