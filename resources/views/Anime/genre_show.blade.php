@extends('layouts.public')

@section('title', $genre->name . ' | AniHub')

@section('content')
<div class="genre-hero" style="position: relative; margin-bottom: 3rem;">
    @if($topAnime->count() > 0)
        @php
            $bgImages = $topAnime->filter(fn($a) => $a->image)->values();
            $count = $bgImages->count();
        @endphp
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 450px; overflow: hidden; z-index: -1;">
            @foreach($bgImages as $index => $anime)
                <img src="{{ asset('storage/' . $anime->image) }}" 
                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(25px) brightness(0.4); transform: scale(1.1); 
                     @if($count > 1) 
                         animation: bgFade{{$count}} {{ $count * 5 }}s infinite {{ $index * 5 }}s; 
                         opacity: 0; 
                     @else 
                         opacity: 1; 
                     @endif">
            @endforeach
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, var(--bg-dark) 0%, transparent 100%);"></div>
        </div>
        
        @if($count > 1)
        <style>
            @keyframes bgFade2 {
                0%, 100% { opacity: 0; }
                20%, 50% { opacity: 1; }
                70% { opacity: 0; }
            }
            @keyframes bgFade3 {
                0%, 100% { opacity: 0; }
                10%, 33.3% { opacity: 1; }
                43.3% { opacity: 0; }
            }
        </style>
        @endif
    @endif
    
    <div class="container" style="padding-top: 5rem; padding-bottom: 2rem; text-align: center;">
        <h1 style="font-size: 3.5rem; font-weight: 800; color: white; text-shadow: 0 4px 15px rgba(0,0,0,0.5); margin-bottom: 1rem;">{{ $genre->name }}</h1>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">Аніме • Жанри • {{ $genre->name }}</p>
    </div>
</div>

<div class="container">
    @if($topAnime->count() > 0)
        <div style="margin-bottom: 4rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; border-left: 4px solid var(--accent-color); padding-left: 1rem;">Найкраще у жанрі</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($topAnime as $index => $anime)
                    <a href="{{ route('anime.show', $anime->id) }}" style="text-decoration: none; color: inherit; display: block;">
                        <div style="position: relative; border-radius: 12px; overflow: hidden; height: 260px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            @if($anime->image)
                                <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; background-color: #252529;"></div>
                            @endif
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 100%);"></div>
                            
                            <!-- Номер в топі -->
                            <div style="position: absolute; top: 10px; left: 10px; background: {{ $index == 0 ? '#f1c40f' : ($index == 1 ? '#bdc3c7' : '#cd7f32') }}; color: {{ $index == 0 ? '#000' : '#fff' }}; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 0.9rem; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                #{{ $index + 1 }}
                            </div>
                            
                            <!-- Рейтинг -->
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: bold; color: #f1c40f; display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-star"></i>
                                {{ number_format($anime->ratings_avg_score ?? 0, 1) }}
                            </div>

                            <div style="position: absolute; bottom: 15px; left: 15px; right: 15px;">
                                <h3 style="font-size: 1.1rem; font-weight: 600; color: white; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $anime->title }}</h3>
                                <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">{{ $anime->year ?? '?' }} • {{ $anime->type ?? 'ТБ-серіал' }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <!-- Основний контент (сітка) -->
        <div style="flex: 1; min-width: 300px;">
            @include('Anime.partials.grid', ['animes' => $animes])
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isFetchingNextPage = false;
        const trigger = document.querySelector('.infinite-scroll-trigger');
        const grid = document.querySelector('.anime-grid');
        
        if (!trigger) return;

        function loadMore() {
            if (isFetchingNextPage) return;
            let nextPageUrl = trigger.getAttribute('data-next-page');
            if (!nextPageUrl) return;

            isFetchingNextPage = true;
            trigger.style.opacity = '1';

            fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                
                let newItems = doc.querySelectorAll('.anime-card');
                newItems.forEach(item => grid.appendChild(item));

                let newTrigger = doc.querySelector('.infinite-scroll-trigger');
                if (newTrigger) {
                    trigger.setAttribute('data-next-page', newTrigger.getAttribute('data-next-page'));
                } else {
                    trigger.remove();
                }
                
                isFetchingNextPage = false;
            })
            .catch(err => {
                console.error(err);
                isFetchingNextPage = false;
            });
        }

        let observer = new IntersectionObserver(function(entries) {
            if(entries[0].isIntersecting) {
                loadMore();
            }
        }, { rootMargin: '200px' });

        observer.observe(trigger);
    });
</script>
@endpush
@endsection
