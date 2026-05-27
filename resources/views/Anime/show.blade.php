@extends('layouts.public')

@section('title', $anime->title . ' - AniHub')

@section('content')
    <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; display: inline-block; margin-bottom: 1.5rem; font-size: 0.9rem;">
        &larr; Назад до каталогу
    </a>

    <div class="anime-details">
        <!-- Ліва колонка (Сайдбар) -->
        <aside class="details-sidebar">
            <div style="position: relative;">
                @if($anime->image)
                    <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="details-poster">
                @else
                    <div class="details-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666;">Немає постера</div>
                @endif

                <div class="rating-badge" style="font-size: 0.9rem; padding: 0.3rem 0.6rem;">
                    <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                    {{ $anime->ratings->count() > 0 ? number_format($anime->ratings->avg('score'), 1) : '0.0' }}
                </div>
            </div>

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
                        <button class="status-option" data-value="watching" data-text="Переглядаю" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='#2ecc71'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Переглядаю</button>
                        <button class="status-option" data-value="plan_to_watch" data-text="В планах" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='#9b59b6'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">В планах</button>
                        <button class="status-option" data-value="completed" data-text="Переглянуто" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='#3498db'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Переглянуто</button>
                        <button class="status-option" data-value="on_hold" data-text="Відкладено" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='#f1c40f'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Відкладено</button>
                        <button class="status-option" data-value="dropped" data-text="Кинуто" style="width: 100%; text-align: center; background: none; border: none; padding: 0.6rem 1rem; color: var(--text-color); cursor: pointer;" onmouseover="this.style.background='#e74c3c'; this.style.color='white'" onmouseout="this.style.background='none'; this.style.color='var(--text-color)'">Кинуто</button>
                    </div>
                </div>

                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <button id="favorite-btn" class="btn btn-outline" style="flex: 1; border-color: {{ $isFavorite ? '#e74c3c' : 'var(--border-color)' }}; color: {{ $isFavorite ? '#e74c3c' : 'var(--text-color)' }}; padding: 0.5rem 0.2rem; font-size: 0.85rem; white-space: nowrap;">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i> В обране
                        </button>
                        <button id="collection-btn" class="btn btn-outline" style="flex: 1; padding: 0.5rem 0.2rem; font-size: 0.85rem; white-space: nowrap;" onclick="openCollectionModal()">
                            <i class="fas fa-folder-plus"></i> В колекцію
                        </button>
                    </div>
                    <button class="btn btn-outline" style="width: 100%;" onclick="copyToClipboard(window.location.href)">
                        <i class="fas fa-share-alt"></i> Поділитися
                    </button>

                    <!-- Toast Notification -->
                    <div id="toast" style="visibility: hidden; min-width: 250px; background-color: var(--bg-card); border: 1px solid var(--border-color); color: #fff; text-align: center; border-radius: var(--radius-md); padding: 16px; position: fixed; z-index: 1000; right: 30px; bottom: 30px; font-size: 1rem; font-weight: 500; box-shadow: 0px 4px 15px rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s, bottom 0.3s;">
                        Посилання скопійовано!
                    </div>

                    <script>
                    function copyToClipboard(text) {
                        navigator.clipboard.writeText(text).then(function() {
                            var toast = document.getElementById("toast");
                            toast.style.visibility = "visible";
                            toast.style.opacity = "1";
                            toast.style.bottom = "50px";
                            setTimeout(function(){
                                toast.style.opacity = "0";
                                toast.style.bottom = "30px";
                                setTimeout(function(){ toast.style.visibility = "hidden"; }, 300);
                            }, 3000);
                        });
                    }
                    </script>
                </div>
            @else
                <button class="btn btn-outline" style="width: 100%;" onclick="window.location='{{ route('login') }}'">
                    <i class="fas fa-sign-in-alt"></i> Увійдіть, щоб додати
                </button>
            @endauth

            <ul class="info-list">
                <li>
                    <span>Рік</span>
                    <span><a href="{{ route('home', ['year' => $anime->year]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->year ?? 'Невідомо' }}</a></span>
                </li>
                <li>
                    <span>Формат</span>
                    <span><a href="{{ route('home', ['format' => $anime->format]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->format ?? 'Серіал' }}</a></span>
                </li>
                <li>
                    <span>Країна</span>
                    <span><a href="{{ route('home', ['country' => $anime->country]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->country ?? 'Японія' }}</a></span>
                </li>
                <li>
                    <span>Статус</span>
                    <span><a href="{{ route('home', ['status' => $anime->status]) }}" style="color: var(--accent-color); text-decoration: none;">
                        @if($anime->status == 'ongoing') Онгоїнг
                        @elseif($anime->status == 'completed') Завершено
                        @elseif($anime->status == 'announced') Анонс
                        @else {{ $anime->status ?? 'Невідомо' }}
                        @endif
                    </a></span>
                </li>
                <li>
                    <span>Студія</span>
                    <span><a href="{{ route('home', ['studio' => $anime->studio]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->studio ?? 'Невідомо' }}</a></span>
                </li>
                <li>
                    <span>Озвучка</span>
                    <span><a href="{{ route('home', ['voice_acting' => $anime->voice_acting]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->voice_acting ?? 'Оригінал' }}</a></span>
                </li>
                <li>
                    <span>Епізоди</span>
                    <span>{{ $anime->episodes->count() }}</span>
                </li>
            </ul>
        </aside>

        <!-- Права колонка (Основний контент) -->
        <main class="details-main">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0;">
                <h1 class="details-title" style="margin-bottom: 0;">{{ $anime->title }}</h1>
                <span style="background: var(--gradient-primary); color: white; padding: 0.2rem 0.6rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: bold;">NEW</span>
            </div>
            @if($anime->original_title)
                <h3 style="color: var(--text-muted); margin-top: 0.5rem; margin-bottom: 1.5rem; font-weight: normal; font-size: 1.1rem;">{{ $anime->original_title }}</h3>
            @else
                <div style="margin-bottom: 1.5rem;"></div>
            @endif

            <div class="details-genres" style="margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                @if(isset($anime->genres) && $anime->genres->count() > 0)
                    @foreach($anime->genres as $genre)
                        <span class="genre-tag" style="background: rgba(138, 43, 226, 0.15); color: #b370ff; border: 1px solid rgba(138, 43, 226, 0.3); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">{{ $genre->name }}</span>
                    @endforeach
                @endif
            </div>

            <div class="details-description">
                {!! nl2br(e($anime->description)) !!}
            </div>

            <!-- Плеєр -->
            @php
                $currentEpId = request('ep');
                $currentEpisode = $currentEpId ? $anime->episodes->firstWhere('id', $currentEpId) : $anime->episodes->first();

                $voiceActings = $anime->episodes->map(function($ep) {
                    return trim($ep->title) ?: 'Оригінал';
                })->unique()->values();

                $currentVoice = request('voice');
                if (!$currentVoice && $currentEpisode) {
                    $currentVoice = trim($currentEpisode->title) ?: 'Оригінал';
                } elseif (!$currentVoice && $voiceActings->isNotEmpty()) {
                    $currentVoice = $voiceActings->first();
                }
            @endphp

            <div style="background: var(--bg-dark); padding: 1rem; border-radius: var(--radius-lg); margin-top: 2rem;">
                <!-- Селектори Озвучки -->
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    @if($voiceActings->isNotEmpty())
                    <div style="position: relative; display: inline-block;">
                        <button onclick="document.getElementById('voice-dropdown').classList.toggle('show')" style="background: var(--bg-card); padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; cursor: pointer; border: 1px solid var(--border-color); color: var(--text-color);">
                            {{ $currentVoice }} <i class="fas fa-chevron-down" style="margin-left: 5px;"></i>
                        </button>
                        <div id="voice-dropdown" class="dropdown-content" style="display: none; position: absolute; background-color: var(--bg-card); min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5); z-index: 100; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-top: 5px;">
                            @foreach($voiceActings as $voice)
                                @php
                                    $firstEpForVoice = $anime->episodes->first(function($ep) use ($voice) {
                                        return (trim($ep->title) ?: 'Оригінал') === $voice;
                                    });
                                @endphp
                                <a href="{{ route('anime.show', ['anime' => $anime->id, 'ep' => $firstEpForVoice->id ?? '', 'voice' => $voice]) }}" style="color: var(--text-color); padding: 10px 16px; text-decoration: none; display: block; border-bottom: 1px solid var(--border-color);">{{ $voice }}</a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div style="background: var(--bg-card); padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; color: var(--text-muted);">Озвучка не вказана</div>
                    @endif
                </div>

                <style>
                    .dropdown-content.show { display: block !important; }
                    .dropdown-content a:hover { background-color: var(--bg-dark); }
                </style>
                <script>
                    window.onclick = function(event) {
                        if (!event.target.matches('button') && !event.target.closest('button')) {
                            var dropdowns = document.getElementsByClassName("dropdown-content");
                            for (var i = 0; i < dropdowns.length; i++) {
                                var openDropdown = dropdowns[i];
                                if (openDropdown.classList.contains('show')) {
                                    openDropdown.classList.remove('show');
                                }
                            }
                        }
                    }
                </script>

                <div class="player-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000; border-radius: var(--radius-md);">
                    @if($currentEpisode && $currentEpisode->video_url)
                        @php
                            $rawUrl = $currentEpisode->video_url;
                            $videoUrl = $rawUrl;

                            if (\Illuminate\Support\Str::startsWith($rawUrl, '/storage/episodes/')) {
                                $videoUrl = str_replace('/storage/episodes/', '/stream/episodes/', $rawUrl);
                            }

                            if (preg_match('/src="([^"]+)"/i', $rawUrl, $matches)) {
                                $videoUrl = $matches[1];
                            } elseif (preg_match("/src='([^']+)'/i", $rawUrl, $matches)) {
                                $videoUrl = $matches[1];
                            }

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
                    @forelse($anime->episodes->filter(function($ep) use ($currentVoice) {
                        return (trim($ep->title) ?: 'Оригінал') === $currentVoice;
                    }) as $episode)
                        <a href="{{ route('anime.show', ['anime' => $anime->id, 'ep' => $episode->id, 'voice' => $currentVoice]) }}"
                           class="ep-btn {{ $currentEpisode && $currentEpisode->id == $episode->id ? 'active' : '' }}">
                           {{ $episode->episode_number }} серія
                        </a>
                    @empty
                        <span style="color: var(--text-muted); padding: 0.5rem;">Епізоди відсутні для цієї озвучки</span>
                    @endforelse
                </div>
            </div>
            <!-- Оцінювання -->

            <div style="background: var(--bg-dark); padding: 1.5rem; border-radius: var(--radius-lg); margin-top: 2rem;">
                <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
                    <div style="display: flex; flex-direction: column; align-items: center; padding-right: 2rem; border-right: 1px solid var(--border-color);">
                        <div style="font-size: 2.5rem; font-weight: bold; color: var(--text-primary); line-height: 1;">
                            {{ number_format($anime->ratings->avg('score'), 1) ?? '0.0' }}
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.3rem;">
                            {{ $anime->ratings->count() }} оцінок
                        </div>
                    </div>

                    <div style="flex: 1;">
                        @auth
                            @php
                                $userRating = $anime->ratings->where('user_id', auth()->id())->first();
                                $currentScore = $userRating ? $userRating->score : 0;
                            @endphp
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;" id="star-rating-container" data-current-score="{{ $currentScore }}">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="display: flex; gap: 2px;">
                                        @for($i = 1; $i <= 10; $i++)
                                            <i class="fas fa-star star-rating" data-score="{{ $i }}" style="cursor: pointer; font-size: 1.2rem; color: {{ $i <= $currentScore ? 'var(--accent-color)' : '#444' }}; transition: color 0.1s;"></i>
                                        @endfor
                                    </div>
                                    <span id="star-rating-text" style="color: var(--text-muted); font-size: 0.9rem; margin-left: 0.5rem;">
                                        {{ $currentScore > 0 ? 'Оцінено: ' . $currentScore : 'Оцініть' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--accent-color);">
                                    {{ auth()->user()->username }}
                                </div>
                            </div>

                            <form id="rating-store-form" action="{{ route('ratings.store', $anime->id) }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="score" id="rating-score-input" value="">
                            </form>

                            <form id="rating-delete-form" action="{{ route('ratings.destroy', $anime->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="display: flex; gap: 2px;">
                                        @for($i = 1; $i <= 10; $i++)
                                            <i class="fas fa-star" style="font-size: 1.2rem; color: #444;"></i>
                                        @endfor
                                    </div>
                                    <span style="color: var(--text-muted); font-size: 0.9rem; margin-left: 0.5rem;">Оцініть</span>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--accent-color);">
                                    <a href="{{ route('login') }}" style="color: inherit; text-decoration: none;">Увійдіть</a>, щоб оцінити
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div style="color: var(--text-muted); font-size: 0.9rem;">
                        Середня: <strong style="color: var(--text-primary);">{{ number_format($anime->ratings->avg('score'), 1) ?? '0.0' }}</strong> ({{ $anime->ratings->count() }})
                    </div>
                </div>
            </div>

            @if(isset($similarAnimes) && $similarAnimes->count() > 0)
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem;">Схожі за жанрами</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem;">
                    @foreach($similarAnimes as $similar)
                        <a href="{{ route('anime.show', $similar->id) }}" style="text-decoration: none; color: var(--text-color);">
                            <div style="background: var(--bg-card); border-radius: var(--radius-md); overflow: hidden; transition: transform 0.2s; position: relative;">
                                @if($similar->image)
                                    <img src="{{ asset('storage/' . $similar->image) }}" alt="{{ $similar->title }}" style="width: 100%; height: 200px; object-fit: cover; display: block;">
                                @else
                                    <div style="width: 100%; height: 200px; background: #333; display: flex; align-items: center; justify-content: center;">Немає</div>
                                @endif

                                <div class="rating-badge">
                                    <i class="fas fa-star" style="font-size: 0.7rem;"></i>
                                    {{ $similar->ratings->count() > 0 ? number_format($similar->ratings->avg('score'), 1) : '0.0' }}
                                </div>

                                @auth
                                    @php
                                        $userList = $similar->userLists->where('user_id', auth()->id())->first();
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
                                        <div style="position: absolute; bottom: 33px; left: 0; width: 100%; background-color: {{ $barColor }}; color: white; text-align: center; font-size: 0.75rem; padding: 3px 0; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                                            {{ mb_strtoupper($statusText) }}
                                        </div>
                                    @endif
                                @endauth

                                <div style="padding: 0.5rem; font-size: 0.9rem; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: var(--bg-card);">
                                    {{ $similar->title }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="margin-top: 3rem;">
                <h3 style="margin-bottom: 1.5rem;">Коментарі ({{ $anime->comments->count() }})</h3>

                @auth
                    <form action="{{ route('comments.store', $anime->id) }}" method="POST" style="margin-bottom: 2rem;">
                        @csrf
                        <textarea name="body" class="form-control" rows="3" placeholder="Залиште свій коментар..." required style="margin-bottom: 0.5rem; min-height: 100px; min-width: 100%; max-width: 100%; resize: vertical;"></textarea>
                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-primary">Відправити</button>
                        </div>
                    </form>
                @else
                    <div style="background: var(--bg-dark); padding: 1rem; border-radius: var(--radius-md); text-align: center; margin-bottom: 2rem;">
                        <a href="{{ route('login') }}" style="color: var(--accent-color); text-decoration: none;">Увійдіть</a>, щоб залишати коментарі.
                    </div>
                @endauth

                <div class="comments-list" style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($anime->comments as $comment)
                        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); display: flex; gap: 1rem;">
                            <a href="{{ route('profile.public', $comment->user->id) }}">
                                @if($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--accent-color); display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                                        {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <div>
                                        <a href="{{ route('profile.public', $comment->user->id) }}" style="color: var(--accent-color); font-weight: bold; text-decoration: none;">
                                            {{ $comment->user->username }}
                                        </a>
                                        @if($comment->user->role == 'admin')
                                            <span style="background: #e74c3c; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Admin</span>
                                        @endif
                                        <span style="color: var(--text-muted); font-size: 0.8rem; margin-left: 10px;">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->role == 'admin'))
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;" onclick="return confirm('Ви впевнені?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                                <div style="line-height: 1.5; font-size: 0.95rem;">
                                    {!! nl2br(e($comment->body)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Поки немає коментарів. Будьте першим!
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    @auth
    <div id="collectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 400px; position: relative;">
            <h3 style="margin-top: 0; margin-bottom: 1rem;">Додати в колекцію</h3>

            <div id="collections-list" style="max-height: 200px; overflow-y: auto; margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                @foreach(auth()->user()->collections as $collection)
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
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
        const dropdownBtn = document.getElementById('status-dropdown-btn');
        const dropdownMenu = document.getElementById('status-dropdown-menu');
        const btnText = document.getElementById('status-btn-text');

        dropdownBtn.addEventListener('click', function() {
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('status-dropdown-container').contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        document.querySelectorAll('.status-option').forEach(function(option) {
            option.addEventListener('click', function() {
                let status = this.getAttribute('data-value');
                let text = this.getAttribute('data-text');

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
                    }
                });
            });
        });

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

        function openCollectionModal() {
            document.getElementById('collectionModal').style.display = 'flex';
        }
        function closeCollectionModal() {
            document.getElementById('collectionModal').style.display = 'none';
        }

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

        const starContainer = document.getElementById('star-rating-container');
        if (starContainer) {
            const stars = starContainer.querySelectorAll('.star-rating');
            const currentScore = parseInt(starContainer.getAttribute('data-current-score'));
            const textDisplay = document.getElementById('star-rating-text');
            const storeForm = document.getElementById('rating-store-form');
            const deleteForm = document.getElementById('rating-delete-form');
            const scoreInput = document.getElementById('rating-score-input');

            function updateStars(hoveredScore) {
                stars.forEach(star => {
                    const score = parseInt(star.getAttribute('data-score'));
                    if (score <= hoveredScore) {
                        star.style.color = 'var(--accent-color)';
                    } else {
                        star.style.color = '#444';
                    }
                });
            }

            function updateText(score) {
                if(score > 0) {
                    textDisplay.innerText = 'Оцінено: ' + score;
                } else {
                    textDisplay.innerText = 'Оцініть';
                }
            }

            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    const score = parseInt(this.getAttribute('data-score'));
                    updateStars(score);
                    updateText(score);
                });

                star.addEventListener('click', function() {
                    const selectedScore = parseInt(this.getAttribute('data-score'));
                    if (selectedScore === currentScore) {
                        // Якщо клікнули на ту ж оцінку, скасовуємо її
                        deleteForm.submit();
                    } else {
                        // Інакше зберігаємо нову
                        scoreInput.value = selectedScore;
                        storeForm.submit();
                    }
                });
            });

            starContainer.addEventListener('mouseleave', function() {
                updateStars(currentScore);
            });
        }
    </script>
    @endauth
@endsection
