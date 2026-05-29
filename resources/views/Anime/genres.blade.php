@extends('layouts.public')

@section('title', 'Жанри Аніме | AniHub')

@section('content')
<!-- AOS CSS for animations -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div class="container" style="padding-top: 2rem; padding-bottom: 5rem; overflow-x: hidden;">
    <div style="text-align: center; margin-bottom: 4rem;" data-aos="fade-down">
        <h1 style="font-size: 1.5rem; font-weight: 600; color: var(--text-muted);">Жанри аніме українською</h1>
    </div>

    <div class="genres-wrapper" style="display: flex; flex-direction: column; gap: 7rem; align-items: center;">
        @foreach($genres as $index => $genre)
            @php 
                $isReversed = $index % 2 !== 0; 
                $aosDirection = $isReversed ? 'fade-left' : 'fade-right';
            @endphp
            <div class="genre-section" style="display: flex; gap: 3rem; align-items: center; justify-content: space-between; width: 100%; max-width: 1200px; flex-wrap: wrap; flex-direction: {{ $isReversed ? 'row-reverse' : 'row' }};">
                
                <!-- Інформація про жанр (З'являється другою, delay: 150) -->
                <div class="genre-info" style="flex: 0 0 250px; text-align: left;" data-aos="{{ $aosDirection }}" data-aos-duration="800" data-aos-delay="150">
                    <h2 style="font-size: 3rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem;">{{ $genre->name }}</h2>
                    <div style="width: 60px; height: 6px; background: linear-gradient(90deg, #a855f7, #6366f1); border-radius: 10px; margin-bottom: 1.5rem;"></div>
                    <a href="{{ route('anime.genre.show', $genre->id) }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.8rem; background: rgba(155, 89, 182, 0.1); border: 1px solid rgba(155, 89, 182, 0.3); border-radius: 50px; color: #dcd0ff; text-decoration: none; font-size: 0.95rem; font-weight: 500; transition: all 0.3s;" onmouseover="this.style.background='rgba(155, 89, 182, 0.3)'" onmouseout="this.style.background='rgba(155, 89, 182, 0.1)'">
                        Переглянути всі <i class="fas fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>
                    </a>
                </div>

                <!-- Карусель аніме (З'являється першою, delay: 0) -->
                <div class="genre-carousel" style="flex: 0 1 800px; min-width: 0;" data-aos="{{ $aosDirection }}" data-aos-duration="800" data-aos-delay="0">
                    <div class="swiper genre-swiper-{{ $genre->id }}" style="padding-top: 20px; padding-bottom: 40px; margin-top: -20px; margin-bottom: -40px;" {!! $isReversed ? 'dir="rtl"' : '' !!}>
                        <div class="swiper-wrapper">
                            @foreach($genre->animes as $anime)
                                <div class="swiper-slide" style="width: 190px;" dir="ltr">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="genre-anime-card">
                                        <div class="poster-wrapper">
                                            @if($anime->image)
                                                <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="poster-img">
                                            @else
                                                <div class="poster-img" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає</div>
                                            @endif

                                            <!-- Рейтинг -->
                                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: bold; color: #f1c40f; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                                <i class="fas fa-star"></i>
                                                {{ $anime->ratings->count() > 0 ? number_format($anime->ratings->avg('score'), 1) : '0.0' }}
                                            </div>

                                            <!-- Статус -->
                                            @auth
                                                @php
                                                    $userList = current(array_filter($anime->userLists->all(), function($ul) { return $ul->user_id == auth()->id(); }));
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
                                                @if($statusText)
                                                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; background-color: {{ $barColor }}; backdrop-filter: blur(4px); color: white; text-align: center; font-size: 0.75rem; padding: 5px 0; font-weight: 700; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                                                        {{ mb_strtoupper($statusText) }}
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="anime-title-text" style="margin-top: 1rem; font-weight: 500; font-size: 0.95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-align: left;">
                                            {{ $anime->title }}
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .genre-anime-card {
        display: block;
        text-decoration: none;
        color: inherit;
        outline: none;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .genre-anime-card:hover {
        transform: translateY(-8px);
    }
    .genre-anime-card .poster-wrapper {
        position: relative; 
        border-radius: 14px; 
        overflow: hidden; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); 
        transition: box-shadow 0.3s ease;
    }
    .genre-anime-card .poster-img {
        width: 100%; 
        height: 270px; 
        object-fit: cover; 
        display: block; 
        transition: transform 0.5s ease;
    }
    .genre-anime-card:hover .poster-wrapper {
        box-shadow: 0 15px 30px rgba(0,0,0,0.5);
    }
    .genre-anime-card:hover .poster-img {
        transform: scale(1.05);
    }
    .genre-anime-card .anime-title-text {
        color: var(--text-primary);
        transition: color 0.3s ease;
    }
    .genre-anime-card:hover .anime-title-text {
        color: var(--accent-color);
    }
</style>

<style>
    @media (max-width: 992px) {
        .genre-section {
            flex-direction: column !important;
            gap: 2rem !important;
            align-items: center !important;
        }
        .genre-info {
            flex: none !important;
            width: 100%;
            text-align: center !important;
        }
        .genre-carousel {
            width: 100%;
            margin-left: 0;
            padding-left: 0;
        }
    }
</style>

@push('scripts')
<!-- AOS JS for animations -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animations
        AOS.init({
            once: true,
            offset: 100,
        });

        // Initialize Swipers
        @foreach($genres as $genre)
            new Swiper('.genre-swiper-{{ $genre->id }}', {
                slidesPerView: 'auto',
                spaceBetween: 25,
                freeMode: true,
                grabCursor: true,
            });
        @endforeach
    });
</script>
@endpush
@endsection
