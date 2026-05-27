@extends('layouts.public')

@section('title', $anime->title . ' - AniHub')

@section('content')
    <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; display: inline-block; margin-bottom: 1.5rem; font-size: 0.9rem;">
        &larr; Назад до каталогу
    </a>
    
    <div class="anime-details">
        <!-- Ліва колонка (Сайдбар) -->
        <aside class="details-sidebar">
            @if($anime->image)
                <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="details-poster">
            @else
                <div class="details-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
            @endif

            @auth
                @php
                    $userList = auth()->user()->animeLists()->where('anime_id', $anime->id)->first();
                    $currentStatus = $userList ? $userList->status : null;
                    $isFavorite = $userList ? $userList->is_favorite : false;
                @endphp
                
                <div style="margin-bottom: 1rem;">
                <div style="position: relative; margin-bottom: 0.5rem;" id="status-dropdown-container">
                    @php
                        $statusNames = [
                            '' => '-- Додати до списку --',
                            'watching' => 'Переглядаю',
                            'plan_to_watch' => 'В планах',
                            'completed' => 'Переглянуто',
                            'on_hold' => 'Відкладено',
                            'dropped' => 'Кинуто'
                        ];
                        $btnText = $currentStatus ? $statusNames[$currentStatus] : '-- Додати до списку --';
                    @endphp
                    <button id="status-dropdown-btn" class="btn btn-outline" style="width: 100%; display: flex; align-items: center; position: relative; background: var(--bg-card); color: var(--text-color);">
                        <span id="status-btn-text" style="flex: 1; text-align: center;">{{ $btnText }}</span>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem;"></i>
                    </button>
                    <div id="status-dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); z-index: 100; margin-top: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.5); overflow: hidden;">
                        <button class="status-option" data-value="" data-text="-- Додати до списку --" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer; border-bottom: 1px solid var(--border-color);">-- Видалити зі списку --</button>
                        <button class="status-option" data-value="watching" data-text="Переглядаю" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Переглядаю</button>
                        <button class="status-option" data-value="plan_to_watch" data-text="В планах" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">В планах</button>
                        <button class="status-option" data-value="completed" data-text="Переглянуто" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Переглянуто</button>
                        <button class="status-option" data-value="on_hold" data-text="Відкладено" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Відкладено</button>
                        <button class="status-option" data-value="dropped" data-text="Кинуто" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Кинуто</button>
                    </div>
                </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button id="favorite-btn" class="btn btn-outline" style="flex: 1; border-color: {{ $isFavorite ? '#e74c3c' : 'var(--border-color)' }}; color: {{ $isFavorite ? '#e74c3c' : 'var(--text-color)' }};">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i> Ізбране
                        </button>
                        <button id="collection-btn" class="btn btn-outline" style="flex: 1;" onclick="openCollectionModal()">
                            <i class="fas fa-folder-plus"></i> В колекцію
                        </button>
                    </div>
                </div>
            @else
                <button class="btn btn-outline" style="width: 100%;" onclick="window.location='{{ route('login') }}'">
                    <i class="fas fa-sign-in-alt"></i> Увійдіть, щоб додати
                </button>
            @endauth

            <ul class="info-list">
                <li>
                    <span>Рік</span>
                    <span>{{ $anime->year ?? 'Невідомо' }}</span>
                </li>
                <li>
                    <span>Формат</span>
                    <span>{{ $anime->format ?? 'Серіал' }}</span>
                </li>
                <li>
                    <span>Країна</span>
                    <span>{{ $anime->country ?? 'Японія' }}</span>
                </li>
                <li>
                    <span>Статус</span>
                    <span>
                        @if($anime->status == 'ongoing') Онгоїнг
                        @elseif($anime->status == 'completed') Завершено
                        @elseif($anime->status == 'announced') Анонс
                        @else {{ $anime->status ?? 'Невідомо' }}
                        @endif
                    </span>
                </li>
                <li>
                    <span>Студія</span>
                    <span>{{ $anime->studio ?? 'Невідомо' }}</span>
                </li>
                <li>
                    <span>Озвучка</span>
                    <span>{{ $anime->voice_acting ?? 'Оригінал' }}</span>
                </li>
                <li>
                    <span>Епізоди</span>
                    <span>{{ $anime->episodes->count() }}</span>
                </li>
            </ul>
        </aside>

        <!-- Права колонка (Основний контент) -->
        <main class="details-main">
            <h1 class="details-title">{{ $anime->title }}</h1>
            
            <div class="details-genres">
                @if(isset($anime->genres) && $anime->genres->count() > 0)
                    @foreach($anime->genres as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                    @endforeach
                @else
                    <span class="genre-tag">Аніме</span>
                @endif
            </div>
            
            <div class="details-description">
                {!! nl2br(e($anime->description)) !!}
            </div>

            <!-- Плеєр -->
            @php
                $currentEpId = request('ep');
                $currentEpisode = $currentEpId ? $anime->episodes->firstWhere('id', $currentEpId) : $anime->episodes->first();
            @endphp

            <div style="background: var(--bg-dark); padding: 1rem; border-radius: var(--radius-lg); margin-top: 2rem;">
                <!-- Селектори (Озвучка / Субтитри - заглушка як на AniHub) -->
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    <div style="background: var(--bg-card); padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; cursor: pointer;">Мова/Озвучка <i class="fas fa-chevron-down" style="margin-left: 5px;"></i></div>
                </div>

                <div class="player-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000; border-radius: var(--radius-md);">
                    @if($currentEpisode && $currentEpisode->video_url)
                        @php
                            $rawUrl = $currentEpisode->video_url;
                            $videoUrl = $rawUrl;
                            
                            // 1. Заміна локального шляху для стрімінгу
                            if (\Illuminate\Support\Str::startsWith($rawUrl, '/storage/episodes/')) {
                                $videoUrl = str_replace('/storage/episodes/', '/stream/episodes/', $rawUrl);
                            }
                            
                            // 2. Якщо вставили цілий тег <iframe>, витягуємо з нього посилання (src)
                            if (preg_match('/src="([^"]+)"/i', $rawUrl, $matches)) {
                                $videoUrl = $matches[1];
                            } elseif (preg_match("/src='([^']+)'/i", $rawUrl, $matches)) {
                                $videoUrl = $matches[1];
                            }
                            
                            // 3. Якщо це посилання на відео YouTube (watch?v=...), перетворюємо на embed
                            if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/i', $videoUrl, $matches)) {
                                $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/i', $videoUrl, $matches)) {
                                $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            }
                        @endphp
                        <iframe src="{{ $videoUrl }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted); font-size: 1.2rem;">Відео недоступне або епізоди ще не додані</div>
                    @endif
                </div>
                
                <div class="episode-selector">
                    @forelse($anime->episodes as $episode)
                        <a href="{{ route('anime.show', ['anime' => $anime->id, 'ep' => $episode->id]) }}" 
                           class="ep-btn {{ $currentEpisode && $currentEpisode->id == $episode->id ? 'active' : '' }}">
                           {{ $episode->episode_number }} серія
                        </a>
                    @empty
                        <span style="color: var(--text-muted); padding: 0.5rem;">Епізоди відсутні</span>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    @auth
    <!-- Модалка для колекцій -->
    <div id="collectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 400px;">
            <h3 style="margin-top: 0;">Додати в колекцію</h3>
            <div id="collections-list" style="margin-bottom: 1rem; max-height: 200px; overflow-y: auto;">
                @foreach(auth()->user()->collections as $collection)
                    <label style="display: block; margin-bottom: 0.5rem; cursor: pointer;">
                        <input type="checkbox" class="collection-checkbox" value="{{ $collection->id }}" {{ $collection->animes->contains($anime->id) ? 'checked' : '' }}> 
                        {{ $collection->name }}
                    </label>
                @endforeach
                @if(auth()->user()->collections->isEmpty())
                    <p style="color: var(--text-muted); font-size: 0.9rem;">У вас ще немає колекцій.</p>
                @endif
            </div>
            
            <hr style="border-color: var(--border-color); margin: 1rem 0;">
            
            <form action="{{ route('collections.store') }}" method="POST" style="margin-bottom: 1rem;">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="Нова колекція..." required style="margin-bottom: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Створити</button>
            </form>

            <button class="btn btn-outline" style="width: 100%;" onclick="closeCollectionModal()">Закрити</button>
        </div>
    </div>

    <script>
        // Логіка кастомного дропдауну
        const dropdownBtn = document.getElementById('status-dropdown-btn');
        const dropdownMenu = document.getElementById('status-dropdown-menu');
        const btnText = document.getElementById('status-btn-text');

        dropdownBtn.addEventListener('click', function() {
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });

        // Закривати при кліку поза меню
        document.addEventListener('click', function(e) {
            if (!document.getElementById('status-dropdown-container').contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        document.querySelectorAll('.status-option').forEach(function(option) {
            option.addEventListener('click', function() {
                let status = this.getAttribute('data-value');
                let text = this.getAttribute('data-text');
                
                // Відправляємо AJAX
                fetch('{{ route('user.list.status', $anime->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                }).then(response => response.json()).then(data => {
                    if(data.success) {
                        btnText.innerText = text;
                        dropdownMenu.style.display = 'none';
                        // alert('Список оновлено!'); // Можна вимкнути, щоб не набридало
                    }
                });
            });
        });

        // Ізбране
        document.getElementById('favorite-btn').addEventListener('click', function() {
            fetch('{{ route('user.list.favorite', $anime->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json()).then(data => {
                if(data.success) {
                    let btn = document.getElementById('favorite-btn');
                    let icon = btn.querySelector('i');
                    if (data.is_favorite) {
                        btn.style.borderColor = '#e74c3c';
                        btn.style.color = '#e74c3c';
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        btn.style.borderColor = 'var(--border-color)';
                        btn.style.color = 'var(--text-color)';
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                }
            });
        });

        // Модалка колекцій
        function openCollectionModal() {
            document.getElementById('collectionModal').style.display = 'flex';
        }
        function closeCollectionModal() {
            document.getElementById('collectionModal').style.display = 'none';
        }

        // Додавання/Видалення з колекції
        document.querySelectorAll('.collection-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let collectionId = this.value;
                fetch(`/collections/${collectionId}/anime/{{ $anime->id }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json()).then(data => {
                    if(!data.success) {
                        alert('Помилка оновлення колекції');
                        this.checked = !this.checked;
                    }
                });
            });
        });
    </script>
    @endauth
@endsection