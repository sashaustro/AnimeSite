@extends('layouts.public')

@section('title', 'AniHub - Головна')

@section('content')
<!-- Hero Section / Featured Anime (Placeholder for now, can be dynamic later) -->
@if($animes->count() > 0)
    <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 3rem; background: var(--bg-card); display: flex; align-items: center; min-height: 400px; padding: 2rem;">
        <div style="flex: 1; z-index: 10;">
            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.1;">{{ $animes->first()->title }}</h1>
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: center;">
                <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem;">{{ $animes->first()->year ?? '2024' }}</span>
                <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem;">{{ $animes->first()->format ?? 'Серіал' }}</span>
            </div>
            <p style="color: var(--text-muted); max-width: 600px; margin-bottom: 2rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $animes->first()->description }}
            </p>
            <a href="{{ route('anime.show', $animes->first()->id) }}" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1.1rem;">Детальніше</a>
        </div>
        @if($animes->first()->image)
            <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 60%; mask-image: linear-gradient(to right, transparent, black); -webkit-mask-image: linear-gradient(to right, transparent 10%, black 50%); z-index: 1;">
                <img src="{{ asset('storage/' . $animes->first()->image) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;" alt="poster">
            </div>
        @endif
    </div>
@endif

<!-- Catalog Section -->
<section>
    <h2 class="section-title">{{ $pageTitle ?? 'Останні оновлення' }}</h2>
</section>

<div class="anime-grid">
    @forelse($animes as $item)
        <a href="{{ route('anime.show', $item->id) }}" class="anime-card">
            <span class="badge">NEW</span>
            <div class="rating-badge">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                9.5
            </div>
            
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="anime-poster">
            @else
                <div class="anime-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
            @endif
            
            <div class="anime-info">
                <h3 class="anime-title" title="{{ $item->title }}">{{ $item->title }}</h3>
                <div class="anime-meta">
                    <span>
                        @if(isset($item->genres) && $item->genres->count() > 0)
                            {{ $item->genres->first()->name }}
                        @else
                            Аніме
                        @endif
                    </span>
                    <span>Серія {{ $item->episodes->count() }}</span>
                </div>
            </div>
        </a>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: var(--text-muted);">
            <h2>Каталог порожній...</h2>
        </div>
    @endforelse
</div>

<div style="margin-top: 3rem; display: flex; justify-content: center;">
    {{ $animes->links() }}
</div>
@endsection