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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <!-- Розширюваний пошук -->
            <style>
                .nav-search-container { position: relative; display: flex; align-items: center; }
                #navSearchForm { display: flex; align-items: center; position: relative; }
                #navSearchForm input {
                    width: 0; opacity: 0; padding: 0; border: 1px solid transparent;
                    transition: width 0.3s ease, opacity 0.3s ease, padding 0.3s ease, background 0.3s ease, border-color 0.3s ease;
                    background: transparent; color: var(--text-primary);
                    border-radius: 20px; height: 36px; outline: none; font-size: 0.9rem;
                }
                #navSearchForm.active input {
                    width: 350px; opacity: 1; padding: 0 35px 0 35px;
                    background: transparent;
                    border: 1px solid rgba(255, 255, 255, 0.15);
                }
                #navSearchToggle {
                    background: transparent; border: 1px solid rgba(255, 255, 255, 0.15);
                    color: #a0aec0;
                    border-radius: 20px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
                    cursor: pointer; transition: all 0.3s ease;
                    z-index: 10;
                }
                #navSearchForm.active #navSearchToggle {
                    position: absolute; left: 0; top: 0; height: 100%; border: none;
                    background: transparent; color: var(--text-primary);
                }
                #navSearchToggle:hover { background: rgba(255, 255, 255, 0.1); color: #fff; border-color: rgba(255, 255, 255, 0.3); }
                #navSearchClear {
                    display: none; position: absolute; right: 15px; color: var(--text-muted);
                    cursor: pointer; z-index: 10; font-size: 0.9rem;
                }
                #navSearchForm.active #navSearchClear { display: block; }
                #navSearchClear:hover { color: #dc3545; }
            </style>
            <div class="nav-search-container">
                <form action="{{ route('home') }}" method="GET" id="navSearchForm" class="{{ request('search') ? 'active' : '' }}">
                    <button type="button" id="navSearchToggle" title="Пошук">
                        <i class="fas fa-search"></i>
                    </button>
                    <input type="text" name="search" id="navSearchInput" placeholder="Пошук аніме або @користувач..." value="{{ request('search') }}">
                    <i class="fas fa-times" id="navSearchClear" title="Очистити"></i>
                </form>
            </div>

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
                <a href="{{ route('cabinet.collections') }}" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;" title="Мої колекції"><i class="fas fa-layer-group"></i> Колекції</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('navSearchToggle');
            const searchForm = document.getElementById('navSearchForm');
            const searchInput = document.getElementById('navSearchInput');
            const closeBtn = document.getElementById('navSearchClear');

            if (toggleBtn && searchForm && searchInput && closeBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    if (!searchForm.classList.contains('active')) {
                        e.preventDefault();
                        searchForm.classList.add('active');
                        searchInput.focus();
                    } else if (searchInput.value.trim() !== '') {
                        searchForm.submit();
                    } else {
                        e.preventDefault();
                        searchForm.classList.remove('active');
                    }
                });

                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchInput.value = '';
                    if (window.location.search.includes('search=')) {
                        window.location.href = "{{ route('home') }}";
                    } else {
                        searchForm.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>
