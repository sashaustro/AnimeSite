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
            <div style="position: relative;">
                <span class="badge">NEW</span>
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="anime-poster">
                @else
                    <div class="anime-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
                @endif

                <div class="rating-badge">
                    <i class="fas fa-star" style="font-size: 0.7rem;"></i>
                    {{ $item->ratings->count() > 0 ? number_format($item->ratings->avg('score'), 1) : '0.0' }}
                </div>

                @auth
                    @php
                        $userList = $item->userLists->where('user_id', auth()->id())->first();
                        $barColor = 'transparent';
                        $statusText = '';
                        if($userList) {
                            switch($userList->status) {
                                case 'watching': $barColor = '#2ecc71'; $statusText = 'Переглядаю'; break;
                                case 'plan_to_watch': $barColor = '#9b59b6'; $statusText = 'В планах'; break;
                                case 'completed': $barColor = '#3498db'; $statusText = 'Переглянуто'; break;
                                case 'on_hold': $barColor = '#f1c40f'; $statusText = 'Відкладено'; break;
                                case 'dropped': $barColor = '#e74c3c'; $statusText = 'Кинуто'; break;
                            }
                        }
                    @endphp
                    @if($userList && $statusText)
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; background-color: {{ $barColor }}; color: white; text-align: center; font-size: 0.75rem; padding: 3px 0; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                            {{ mb_strtoupper($statusText) }}
                        </div>
                    @endif
                @endauth
            </div>

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
