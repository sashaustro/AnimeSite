@extends('layouts.public')

@section('title', 'Жанри Аніме | Anime Portal CMS')

@section('content')
<div class="container">
    <div class="breadcrumb" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">AniHub</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Жанри</span>
    </div>
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
            <div style="position: relative;">
                @if($anime->image)
                    <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="anime-poster" loading="lazy">
                @else
                    <div class="anime-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
                @endif

                <div class="rating-badge">
                    <i class="fas fa-star" style="font-size: 0.7rem;"></i>
                    {{ $anime->ratings->count() > 0 ? number_format($anime->ratings->avg('score'), 1) : '0.0' }}
                </div>

                @auth
                    @php
                        $userList = $anime->userLists->where('user_id', auth()->id())->first();
                        $barColor = 'transparent';
                        $statusText = '';
                        if($userList) {
                            switch($userList->status) {
                                case 'watching': $barColor = 'rgba(46, 204, 113, 0.50)'; $statusText = 'Переглядаю'; break;
                                case 'plan_to_watch': $barColor = 'rgba(155, 89, 182, 0.50)'; $statusText = 'В планах'; break;
                                case 'completed': $barColor = 'rgba(52, 152, 219, 0.50)'; $statusText = 'Переглянуто'; break;
                                case 'on_hold': $barColor = 'rgba(241, 196, 15, 0.50)'; $statusText = 'Відкладено'; break;
                                case 'dropped': $barColor = 'rgba(231, 76, 60, 0.50)'; $statusText = 'Кинуто'; break;
                            }
                        }
                    @endphp
                    @if($userList && $statusText)
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; background-color: {{ $barColor }}; backdrop-filter: blur(4px); color: white; text-align: center; font-size: 0.75rem; padding: 3px 0; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                            {{ mb_strtoupper($statusText) }}
                        </div>
                    @endif
                @endauth
            </div>
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
                <h2>На даний момент нічого ще не додано.</h2>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($animes->hasMorePages())
        <div class="infinite-scroll-trigger" data-next-page="{{ $animes->nextPageUrl() }}" style="text-align: center; padding: 2rem; color: var(--accent-color);">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
    @endif
    <div style="display: none;" class="pagination-wrapper">
        {{ $animes->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isFetchingNextPage = false;
        let infiniteObserver = null;

        function setupInfiniteScroll() {
            if (infiniteObserver) {
                infiniteObserver.disconnect();
            }

            const trigger = document.querySelector('.infinite-scroll-trigger');
            if (!trigger) return;

            infiniteObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isFetchingNextPage) {
                    loadNextPage(trigger.dataset.nextPage);
                }
            }, { rootMargin: '300px' });

            infiniteObserver.observe(trigger);
        }

        function loadNextPage(url) {
            if (!url) return;
            isFetchingNextPage = true;
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newItems = doc.querySelectorAll('.anime-card');
                    const grid = document.querySelector('.anime-grid');
                    if(grid) {
                        newItems.forEach(item => grid.appendChild(item));
                    }
                    
                    const newTrigger = doc.querySelector('.infinite-scroll-trigger');
                    const oldTrigger = document.querySelector('.infinite-scroll-trigger');
                    
                    if (newTrigger && oldTrigger) {
                        oldTrigger.dataset.nextPage = newTrigger.dataset.nextPage;
                    } else if (oldTrigger) {
                        oldTrigger.remove();
                    }
                    
                    const newPagination = doc.querySelector('.pagination-wrapper');
                    const oldPagination = document.querySelector('.pagination-wrapper');
                    if (newPagination && oldPagination) {
                        oldPagination.innerHTML = newPagination.innerHTML;
                    }
                    
                    isFetchingNextPage = false;
                })
                .catch(() => {
                    isFetchingNextPage = false;
                });
        }

        setupInfiniteScroll();
    });
</script>
@endpush
@endsection
