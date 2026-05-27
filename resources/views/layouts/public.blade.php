<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Anime Portal CMS')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="nav-brand">ANI<span>HUB</span></a>
        
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Аніме</a>
            <a href="{{ route('anime.genres') }}" class="nav-link {{ request()->routeIs('anime.genres') ? 'active' : '' }}">Жанри</a>
            <a href="{{ route('anime.ongoing') }}" class="nav-link {{ request()->routeIs('anime.ongoing') ? 'active' : '' }}">Онгоїнги</a>
            <a href="{{ route('anime.top') }}" class="nav-link {{ request()->routeIs('anime.top') ? 'active' : '' }}">Топ рейтингу</a>
        </div>

        <div class="nav-links" style="gap: 0.8rem;">
            <!-- Пошук -->
            <form action="{{ route('home') }}" method="GET" style="display: flex; align-items: center;">
                <input type="text" name="search" placeholder="Пошук аніме..." value="{{ request('search') }}" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.9rem; outline: none; width: 160px; max-width: 100%;">
                <button type="submit" style="background: none; border: none; color: var(--text-muted); margin-left: -30px; cursor: pointer;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            @auth
                @if(auth()->user()->isAdmin())
                    <div style="position: relative; display: inline-block;" onmouseenter="this.querySelector('.dropdown-wrapper').style.display='block'" onmouseleave="this.querySelector('.dropdown-wrapper').style.display='none'">
                        <button class="btn btn-outline" style="font-size: 0.8rem; padding: 0.3rem 0.8rem; display: flex; align-items: center; gap: 5px;">
                            Адмін <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="dropdown-wrapper" style="display: none; position: absolute; right: 0; top: 100%; padding-top: 15px; z-index: 9999; min-width: 200px;">
                            <div style="background: var(--surface-color, #1c1e2d); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.5rem 0; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">
                                <a href="{{ route('anime.admin_index') }}" style="display: block; padding: 0.5rem 1rem; color: var(--text-primary); text-decoration: none; font-size: 0.9rem;" onmouseenter="this.style.background='var(--bg-card-hover, #26293d)'" onmouseleave="this.style.background='transparent'">Панель керування</a>
                                <a href="{{ route('anime.create') }}" style="display: block; padding: 0.5rem 1rem; color: var(--text-primary); text-decoration: none; font-size: 0.9rem;" onmouseenter="this.style.background='var(--bg-card-hover, #26293d)'" onmouseleave="this.style.background='transparent'">Додати аніме</a>
                                <a href="{{ route('admin.users.index') }}" style="display: block; padding: 0.5rem 1rem; color: var(--text-primary); text-decoration: none; font-size: 0.9rem;" onmouseenter="this.style.background='var(--bg-card-hover, #26293d)'" onmouseleave="this.style.background='transparent'">Керування користувачами</a>
                            </div>
                        </div>
                    </div>
                @endif
                <a href="{{ route('cabinet.lists') }}" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;" title="Мої списки"><i class="fas fa-bookmark"></i> Закладки</a>
                <a href="{{ route('cabinet') }}" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;">Кабінет</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;">Вийти</button>
                </form>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn btn-outline">Увійти</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Реєстрація</a>
                @endif
            @endauth
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
