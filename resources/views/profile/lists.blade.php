@extends('layouts.public')

@section('title', 'Мої списки - AniHub')

@section('content')
<div class="container">
    <div style="display: flex; align-items: center; margin-bottom: 2rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Мої списки</h2>
        <a href="{{ route('cabinet') }}" class="btn btn-outline" style="margin-left: auto;">В кабінет</a>
    </div>

    <!-- Фільтри та сортування -->
    <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
        
        <form action="{{ route('cabinet.lists') }}" method="GET" id="lists-filter-form" style="display: flex; gap: 1.5rem; flex-wrap: wrap; flex: 1;">
            
            <!-- Статус -->
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">Список</label>
                @php
                    $statusNames = [
                        'all' => 'Всі',
                        'watching' => 'Переглядаю',
                        'plan_to_watch' => 'В планах',
                        'completed' => 'Переглянуто',
                        'on_hold' => 'Відкладено',
                        'dropped' => 'Кинуто',
                        'favorites' => 'Ізбране'
                    ];
                @endphp
                <div style="position: relative;" class="custom-dropdown-container">
                    <input type="hidden" name="status" id="status-input" value="{{ $status }}">
                    <button type="button" class="btn btn-outline custom-dropdown-btn" style="min-width: 160px; display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); color: var(--text-color);">
                        <span class="dropdown-text">{{ $statusNames[$status] ?? 'Всі' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="custom-dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); z-index: 100; margin-top: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.5); overflow: hidden;">
                        @foreach($statusNames as $val => $name)
                            <div class="dropdown-option" data-value="{{ $val }}" data-target="status-input" style="padding: 0.6rem 1rem; cursor: pointer; color: var(--text-color);" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">{{ $name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Сортування -->
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">Сортування</label>
                @php
                    $sortNames = [
                        'added_desc' => 'Спочатку нові (за додаванням)',
                        'year_desc' => 'Рік випуску (від нових)',
                        'title_asc' => 'За алфавітом (А-Я)'
                    ];
                @endphp
                <div style="position: relative;" class="custom-dropdown-container">
                    <input type="hidden" name="sort" id="sort-input" value="{{ $sort }}">
                    <button type="button" class="btn btn-outline custom-dropdown-btn" style="min-width: 200px; display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); color: var(--text-color);">
                        <span class="dropdown-text">{{ $sortNames[$sort] ?? 'Спочатку нові' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="custom-dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); z-index: 100; margin-top: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.5); overflow: hidden; white-space: nowrap;">
                        @foreach($sortNames as $val => $name)
                            <div class="dropdown-option" data-value="{{ $val }}" data-target="sort-input" style="padding: 0.6rem 1rem; cursor: pointer; color: var(--text-color);" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">{{ $name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </form>

        @if($lists->count() > 0)
            <button onclick="randomAnime()" class="btn btn-primary" style="align-self: flex-end;">
                <i class="fas fa-random"></i> Випадкове аніме
            </button>
        @endif
    </div>

    <!-- Список аніме -->
    @if($lists->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            @foreach($lists as $list)
                @if($list->anime)
                <div class="anime-card" style="background: var(--bg-card); border-radius: var(--radius-md); overflow: hidden; position: relative;">
                    <a href="{{ route('anime.show', $list->anime->id) }}">
                        @if($list->anime->image)
                            <img src="{{ asset('storage/' . $list->anime->image) }}" alt="poster" style="width: 100%; aspect-ratio: 2/3; object-fit: cover;">
                        @else
                            <div style="width: 100%; aspect-ratio: 2/3; background: #333; display: flex; align-items: center; justify-content: center; color: #666;">Немає</div>
                        @endif
                        <div style="padding: 1rem;">
                            <h4 style="font-size: 1rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-primary);">{{ $list->anime->title }}</h4>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $list->anime->year ?? '?' }} &bull; {{ $list->anime->status ?? '?' }}</div>
                        </div>
                    </a>
                    
                    @if($list->is_favorite)
                        <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: #e74c3c; padding: 5px; border-radius: 50%;">
                            <i class="fas fa-heart"></i>
                        </div>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <i class="fas fa-box-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-muted);">Список порожній</h3>
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Ви ще не додали жодного аніме до цього списку.</p>
            <a href="{{ route('home') }}" class="btn btn-outline">Перейти до каталогу</a>
        </div>
    @endif
</div>

<!-- Модалка випадкового аніме -->
<div id="randomAnimeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 500px; position: relative;">
        <button onclick="document.getElementById('randomAnimeModal').style.display='none'" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--accent-color);">
            <i class="fas fa-dice"></i> Випадкове аніме для вас
        </h3>
        
        <div id="random-anime-content" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <!-- Вміст генерується через JS -->
        </div>

        <div style="display: flex; gap: 1rem;">
            <a id="random-anime-link" href="#" class="btn btn-primary" style="flex: 1; text-align: center;">Дивитись</a>
            <button onclick="randomAnime()" class="btn btn-outline" style="flex: 1;"><i class="fas fa-redo"></i> Інше</button>
        </div>
    </div>
</div>

<script>
    const animeData = [
        @foreach($lists as $list)
            @if($list->anime)
            {
                id: {{ $list->anime->id }},
                title: @json($list->anime->title),
                image: "{{ $list->anime->image ? asset('storage/' . $list->anime->image) : '' }}",
                year: "{{ $list->anime->year ?? 'Невідомо' }}",
                description: @json(\Illuminate\Support\Str::limit($list->anime->description, 150))
            },
            @endif
        @endforeach
    ];

    function randomAnime() {
        if(animeData.length === 0) return;
        
        const randomIndex = Math.floor(Math.random() * animeData.length);
        const anime = animeData[randomIndex];
        
        let imgHtml = anime.image ? `<img src="${anime.image}" style="width: 120px; border-radius: var(--radius-md); object-fit: cover;">` : `<div style="width: 120px; background: #333; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">Немає</div>`;
        
        document.getElementById('random-anime-content').innerHTML = `
            ${imgHtml}
            <div>
                <h4 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">${anime.title}</h4>
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Рік: ${anime.year}</div>
                <div style="font-size: 0.9rem; color: var(--text-primary); line-height: 1.4;">${anime.description}</div>
            </div>
        `;
        
        document.getElementById('random-anime-link').href = `/anime/${anime.id}`;
        document.getElementById('randomAnimeModal').style.display = 'flex';
    }

    // Логіка кастомних дропдаунів
    document.querySelectorAll('.custom-dropdown-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Закриваємо всі інші
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                if (menu !== this.nextElementSibling) menu.style.display = 'none';
            });
            // Тогл поточного
            const menu = this.nextElementSibling;
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        });
    });

    // Клік по опції
    document.querySelectorAll('.dropdown-option').forEach(option => {
        option.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).value = val;
            
            // Сабмітимо форму
            document.getElementById('lists-filter-form').submit();
        });
    });

    // Закриття при кліку поза меню
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown-container')) {
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
</script>
@endsection
