@extends('layouts.public')

@section('title', $anime->title . ' - AniHub')

@section('content')
    <div class="breadcrumb" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">AniHub</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">Аніме</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <span style="color: var(--text-primary); font-weight: 600;" title="{{ $anime->title }}">{{ mb_strlen($anime->title) > 35 ? mb_substr($anime->title, 0, 35) . '...' : $anime->title }}</span>
    </div>

    <div class="anime-details">
        <!-- Ліва колонка (Сайдбар) -->
        <aside class="details-sidebar">
            @php
                $userList = null;
                $currentStatus = null;
                $isFavorite = false;
                if(auth()->check()) {
                    $userList = auth()->user()->animeLists()->where('anime_id', $anime->id)->first();
                    $currentStatus = $userList ? $userList->status : null;
                    $isFavorite = $userList ? $userList->is_favorite : false;
                }
            @endphp
            
            <div style="position: relative; overflow: hidden; border-radius: 12px; margin-bottom: 1rem;">
                @if($anime->image)
                    <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}" class="details-poster" style="margin-bottom: 0; display: block; width: 100%;">
                @else
                    <div class="details-poster" style="background-color: #252529; display: flex; align-items: center; justify-content: center; color: #666; margin-bottom: 0; width: 100%;">Немає постера</div>
                @endif

                <div class="rating-badge" style="font-size: 0.9rem; padding: 0.3rem 0.6rem; z-index: 10;">
                    <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                    {{ $anime->ratings->count() > 0 ? number_format($anime->ratings->avg('score'), 1) : '0.0' }}
                </div>
                
                @auth
                    @php
                        $userList = auth()->user()->animeLists()->where('anime_id', $anime->id)->first();
                        $currentStatus = $userList ? $userList->status : null;
                        $barColor = 'transparent';
                        $statusText = '';
                        switch($currentStatus) {
                            case 'watching': $barColor = 'rgba(46, 204, 113, 0.50)'; $statusText = 'Переглядаю'; break;
                            case 'plan_to_watch': $barColor = 'rgba(155, 89, 182, 0.50)'; $statusText = 'В планах'; break;
                            case 'completed': $barColor = 'rgba(52, 152, 219, 0.50)'; $statusText = 'Переглянуто'; break;
                            case 'on_hold': $barColor = 'rgba(241, 196, 15, 0.50)'; $statusText = 'Відкладено'; break;
                            case 'dropped': $barColor = 'rgba(231, 76, 60, 0.50)'; $statusText = 'Кинуто'; break;
                        }
                    @endphp
                    <div id="poster-status-bar" style="position: absolute; bottom: 0; left: 0; width: 100%; background-color: {{ $barColor }}; backdrop-filter: blur(4px); color: white; text-align: center; font-size: 0.9rem; padding: 6px 0; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.6); z-index: 5; display: {{ $statusText ? 'block' : 'none' }};">
                        {{ mb_strtoupper($statusText) }}
                    </div>
                @endauth
            </div>

            @auth

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
                    <span>
                        @if(!empty($anime->year))
                            <a href="{{ route('home', ['year' => $anime->year]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->year }}</a>
                        @else
                            —
                        @endif
                    </span>
                </li>
                <li>
                    <span>Сезон</span>
                    <span>
                        @if(!empty($anime->season))
                            <a href="{{ route('home', ['season' => $anime->season]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->season }}</a>
                        @else
                            —
                        @endif
                    </span>
                </li>
                <li>
                    <span>Формат</span>
                    <span>
                        @if(!empty($anime->format) && $anime->format !== 'Невідомо')
                            <a href="{{ route('home', ['format' => $anime->format]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->format }}</a>
                        @else
                            —
                        @endif
                    </span>
                </li>
                <li>
                    <span>Країна</span>
                    <span>
                        @if(!empty($anime->country) && $anime->country !== 'Невідомо')
                            <a href="{{ route('home', ['country' => $anime->country]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->country }}</a>
                        @else
                            —
                        @endif
                    </span>
                </li>
                <li>
                    <span>Статус</span>
                    <span>
                        @if(!empty($anime->status))
                            <a href="{{ route('home', ['status' => $anime->status]) }}" style="color: var(--accent-color); text-decoration: none;">
                                @if($anime->status == 'ongoing') Онгоїнг
                                @elseif($anime->status == 'completed') Завершено
                                @elseif($anime->status == 'announced') Анонс
                                @elseif($anime->status == 'paused') Призупинено
                                @elseif($anime->status == 'cancelled') Скасовано
                                @else {{ $anime->status }}
                                @endif
                            </a>
                        @else
                            —
                        @endif
                    </span>
                </li>
                <li>
                    <span>Студія</span>
                    <span>
                        @if(!empty($anime->studio) && $anime->studio !== 'Невідомо')
                            <a href="{{ route('home', ['studio' => $anime->studio]) }}" style="color: var(--accent-color); text-decoration: none;">{{ $anime->studio }}</a>
                        @else
                            —
                        @endif
                    </span>
                </li>

                <li>
                    <span>Першоджерело</span>
                    <span>{{ !empty($anime->source) ? $anime->source : '—' }}</span>
                </li>
                <li>
                    <span>Автор</span>
                    <span>{{ !empty($anime->author) ? $anime->author : '—' }}</span>
                </li>
                <li>
                    <span>Тривалість</span>
                    <span>{{ !empty($anime->duration) ? $anime->duration : '—' }}</span>
                </li>
                @if($anime->status == 'ongoing' && !empty($anime->broadcast_day))
                <li>
                    <span>День виходу</span>
                    <span>{{ $anime->broadcast_day }}</span>
                </li>
                @endif
                <li>
                    <span>Епізоди</span>
                    <span>
                        @php
                            $maxEpisode = $anime->episodes->max('episode_number') ?? 0;
                            $totalEpisodes = $anime->total_episodes ?? '?';
                        @endphp
                        {{ $maxEpisode }} із {{ $totalEpisodes }}
                    </span>
                </li>
            </ul>
        </aside>

        <!-- Права колонка (Основний контент) -->
        <main class="details-main">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <div style="display: flex; flex-direction: column;">
                        <h1 class="details-title" style="margin-bottom: 0; display: inline-flex; align-items: center; flex-wrap: wrap; gap: 0.8rem;">
                            <span>
                                @if(mb_strlen($anime->title) > 50)
                                    <span class="expandable-text" data-full="{{ htmlspecialchars($anime->title) }}" data-short="{{ htmlspecialchars(mb_substr($anime->title, 0, 50) . '...') }}">{{ mb_substr($anime->title, 0, 50) }}...</span>
                                    <button type="button" class="btn-expand-title" onclick="toggleExpandText(this)">розгорнути</button>
                                @else
                                    {{ $anime->title }}
                                @endif
                            </span>
                            <span style="background: var(--gradient-primary); color: white; padding: 0.2rem 0.6rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: bold; margin-top: -2px;">NEW</span>
                        </h1>
                        @if($anime->original_title)
                            <div style="color: var(--text-muted); font-size: 1.1rem; margin-top: 0.2rem; font-family: 'Inter', sans-serif;">
                                @if(mb_strlen($anime->original_title) > 50)
                                    <span class="expandable-text" data-full="{{ htmlspecialchars($anime->original_title) }}" data-short="{{ htmlspecialchars(mb_substr($anime->original_title, 0, 50) . '...') }}">{{ mb_substr($anime->original_title, 0, 50) }}...</span>
                                    <button type="button" class="btn-expand-title" onclick="toggleExpandText(this)">розгорнути</button>
                                @else
                                    {{ $anime->original_title }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @auth
                <button onclick="openReportModal('info', {{ $anime->id }})" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem; border-color: #6c757d; color: #6c757d;" title="Повідомити про помилку в описі">
                    <i class="fas fa-exclamation-triangle"></i> Помилка в описі
                </button>
                @endauth
            </div>

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

            <div id="player-section" style="background: var(--bg-dark); padding: 1rem; border-radius: var(--radius-lg); margin-top: 2rem;">
                <!-- Селектори Озвучки -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="color: var(--text-muted); font-size: 0.95rem; font-weight: 500;">Озвучка:</span>
                        @if($voiceActings->isNotEmpty())
                        <div style="position: relative; display: inline-block;">
                            <button onclick="document.getElementById('voice-dropdown').classList.toggle('show')" style="background: var(--bg-card); padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; cursor: pointer; border: 1px solid var(--border-color); color: var(--text-color);">
                                {{ $currentVoice }} <i class="fas fa-chevron-down" style="margin-left: 5px;"></i>
                            </button>
                            <div id="voice-dropdown" class="dropdown-content" style="display: none; position: absolute; background-color: var(--bg-card); min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5); z-index: 100; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-top: 5px;">
                                @foreach($voiceActings as $voice)
                                    @php
                                        $voiceEpisodes = $anime->episodes->filter(function($ep) use ($voice) {
                                            return (trim($ep->title) ?: 'Оригінал') === $voice;
                                        });
                                        $firstEpForVoice = $voiceEpisodes->first();
                                        $epCount = $voiceEpisodes->count();
                                        
                                        // Відмінювання слова "епізод"
                                        $lastDigit = $epCount % 10;
                                        $lastTwo = $epCount % 100;
                                        $epWord = 'епізодів';
                                        if ($lastDigit == 1 && $lastTwo != 11) {
                                            $epWord = 'епізод';
                                        } elseif (in_array($lastDigit, [2, 3, 4]) && !in_array($lastTwo, [12, 13, 14])) {
                                            $epWord = 'епізоди';
                                        }
                                    @endphp
                                    <a href="{{ route('anime.show', ['anime' => $anime->id, 'ep' => $firstEpForVoice->id ?? '', 'voice' => $voice]) }}#player-section" style="color: var(--text-color); padding: 10px 16px; text-decoration: none; display: block; border-bottom: 1px solid var(--border-color);">
                                        <div style="font-weight: 500; margin-bottom: 2px;">{{ $voice }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $epCount }} {{ $epWord }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div style="background: var(--bg-card); padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; color: var(--text-muted);">Озвучка не вказана</div>
                        @endif
                    </div>
                    
                    @auth
                    <button onclick="openReportModal('player', {{ $anime->id }})" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem; border-color: #6c757d; color: #6c757d;" title="Повідомити про помилку в плеєрі">
                        <i class="fas fa-exclamation-triangle"></i> Помилка в плеєрі
                    </button>
                    @endauth
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
                        <a href="{{ route('anime.show', ['anime' => $anime->id, 'ep' => $episode->id, 'voice' => $currentVoice]) }}#player-section"
                           class="ep-btn {{ $currentEpisode && $currentEpisode->id == $episode->id ? 'active' : '' }}">
                           {{ $episode->episode_number }} серія
                        </a>
                    @empty
                        <span style="color: var(--text-muted); padding: 0.5rem;">Епізоди відсутні для цієї озвучки</span>
                    @endforelse
                </div>
            </div>
            <!-- Оцінювання -->

            <div id="rating-wrapper" style="background: var(--bg-dark); padding: 1.5rem; border-radius: var(--radius-lg); margin-top: 2rem;">
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

            <!-- У списках у користувачів -->
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary);">У списках у користувачів</h3>
                <div style="background: var(--bg-dark); padding: 1.5rem; border-radius: var(--radius-lg); display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 1rem; border: 1px solid var(--border-color);">
                    <div style="text-align: center; flex: 1; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #2ecc71;">{{ $listStats['watching'] ?? 0 }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Переглядаю</div>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--border-color); opacity: 0.5;"></div>
                    <div style="text-align: center; flex: 1; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #9b59b6;">{{ $listStats['plan_to_watch'] ?? 0 }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">В планах</div>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--border-color); opacity: 0.5;"></div>
                    <div style="text-align: center; flex: 1; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #3498db;">{{ $listStats['completed'] ?? 0 }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Переглянуто</div>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--border-color); opacity: 0.5;"></div>
                    <div style="text-align: center; flex: 1; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #f1c40f;">{{ $listStats['on_hold'] ?? 0 }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Відкладено</div>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--border-color); opacity: 0.5;"></div>
                    <div style="text-align: center; flex: 1; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #e74c3c;">{{ $listStats['dropped'] ?? 0 }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Кинуто</div>
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
                                                case 'watching': $barColor = 'rgba(46, 204, 113, 0.50)'; $statusText = 'Переглядаю'; break;
                                                case 'plan_to_watch': $barColor = 'rgba(155, 89, 182, 0.50)'; $statusText = 'В планах'; break;
                                                case 'completed': $barColor = 'rgba(52, 152, 219, 0.50)'; $statusText = 'Переглянуто'; break;
                                                case 'on_hold': $barColor = 'rgba(241, 196, 15, 0.50)'; $statusText = 'Відкладено'; break;
                                                case 'dropped': $barColor = 'rgba(231, 76, 60, 0.50)'; $statusText = 'Кинуто'; break;
                                            }
                                        }
                                    @endphp
                                    @if($userList && $statusText)
                                        <div style="position: absolute; bottom: 33px; left: 0; width: 100%; background-color: {{ $barColor }}; backdrop-filter: blur(4px); color: white; text-align: center; font-size: 0.75rem; padding: 3px 0; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
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

            <div id="comments-section" style="margin-top: 2rem; background: var(--bg-dark); padding: 1.5rem; border-radius: var(--radius-lg); max-width: 850px; margin-left: auto; margin-right: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h3 style="margin: 0; color: var(--text-primary);">Коментарі <span style="color: var(--text-muted); font-size: 1rem; margin-left: 5px;">{{ $commentsList->count() }}</span></h3>
                    <div style="display: flex; background: var(--bg-card); border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'new']) }}#comments-section" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; border: none; border-radius: 0; background: {{ request('sort', 'new') == 'new' ? 'var(--accent-color)' : 'transparent' }}; color: {{ request('sort', 'new') == 'new' ? 'white' : 'var(--text-muted)' }}; transition: background 0.2s;">Нові</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'old']) }}#comments-section" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; border: none; border-radius: 0; background: {{ request('sort') == 'old' ? 'var(--accent-color)' : 'transparent' }}; color: {{ request('sort') == 'old' ? 'white' : 'var(--text-muted)' }}; transition: background 0.2s;">Старі</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}#comments-section" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; border: none; border-radius: 0; background: {{ request('sort') == 'popular' ? 'var(--accent-color)' : 'transparent' }}; color: {{ request('sort') == 'popular' ? 'white' : 'var(--text-muted)' }}; transition: background 0.2s;">Популярні</a>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->isMuted())
                        <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid #e74c3c; border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                            <div style="color: #e74c3c; font-size: 2rem;">
                                <i class="fas fa-comment-slash"></i>
                            </div>
                            <div>
                                <h4 style="color: #e74c3c; margin: 0 0 0.5rem 0; font-size: 1.1rem;">Вам заборонено залишати коментарі</h4>
                                <p style="color: var(--text-primary); margin: 0; font-size: 0.95rem;">
                                    Мут діятиме до: <strong id="muted-until-time" data-timestamp="{{ auth()->user()->muted_until->timestamp }}">{{ auth()->user()->muted_until->format('d.m.Y H:i:s') }} UTC</strong>.
                                    <br>
                                    <span style="color: var(--text-muted);">Причина: {{ auth()->user()->mute_reason ?? 'Не вказана' }}</span>
                                </p>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const el = document.getElementById('muted-until-time');
                                if (el) {
                                    const timestamp = parseInt(el.getAttribute('data-timestamp')) * 1000;
                                    const date = new Date(timestamp);
                                    el.innerText = date.toLocaleString('uk-UA', { 
                                        day: '2-digit', month: '2-digit', year: 'numeric', 
                                        hour: '2-digit', minute: '2-digit', second: '2-digit' 
                                    });
                                }
                            });
                        </script>
                    @else
                        <div style="background: var(--bg-card); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
                            <form action="{{ route('comments.store', $anime->id) }}" method="POST" style="position: relative;">
                                @csrf
                                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 0.4rem; min-width: 60px;">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div style="width: 45px; height: 45px; border-radius: 50%; background: var(--bg-dark); display: flex; align-items: center; justify-content: center; color: var(--text-muted); border: 1px solid var(--border-color);">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <span style="font-size: 0.75rem; color: var(--text-muted); text-align: center; font-weight: 500;">{{ auth()->user()->username }}</span>
                                    </div>
                                    <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; transition: border-color 0.2s, box-shadow 0.2s;">
                                        <textarea name="body" rows="2" placeholder="Залиште свій коментар..." required style="width: 100%; min-height: 80px; background: transparent; border: none; padding: 12px 16px; resize: none; overflow: hidden; outline: none; color: var(--text-primary); font-size: 0.95rem;" onfocus="this.parentElement.style.borderColor='var(--accent-color)'; this.parentElement.style.boxShadow='0 0 0 1px var(--accent-color)';" onblur="this.parentElement.style.borderColor='var(--border-color)'; this.parentElement.style.boxShadow='none';" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                        <div style="padding: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                                            <div style="position: relative;">
                                                <button type="button" id="emoji-picker-btn" style="background: transparent; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 50%; transition: background 0.2s, color 0.2s;" onmouseover="this.style.color='var(--accent-color)'; this.style.background='rgba(255,255,255,0.05)';" onmouseout="this.style.color='var(--text-muted)'; this.style.background='transparent';" title="Додати емодзі">
                                                    <i class="far fa-smile"></i>
                                                </button>
                                            </div>
                                            <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1.2rem; border-radius: 6px; font-weight: 500; font-size: 0.85rem;">Відправити</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); text-align: center; margin-bottom: 2rem; border: 1px solid var(--border-color);">
                        <a href="{{ route('login') }}" style="color: var(--accent-color); text-decoration: none; font-weight: 500;">Увійдіть</a>, щоб залишити коментар
                    </div>
                @endauth

                <div class="comments-list" style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($commentsList as $comment)
                        <div class="comment-item" style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); position: relative; transition: border-color 0.2s;">
                            
                            <div style="position: absolute; right: 1.5rem; top: 1.5rem; display: flex; gap: 0.5rem; z-index: 10;" class="comment-actions">
                                @if(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->role == 'admin'))
                                    @if(auth()->id() == $comment->user_id)
                                        <button onclick="editComment({{ $comment->id }})" class="comment-action-btn edit-btn" title="Редагувати">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    <button onclick="confirmDeleteComment({{ $comment->id }})" class="comment-action-btn delete-btn" title="Видалити">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-comment-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                                
                                @if(auth()->check() && auth()->id() != $comment->user_id)
                                    <button onclick="openReportModal('comment', {{ $comment->id }})" class="comment-action-btn report-btn" title="Поскаржитися">
                                        <i class="fas fa-flag"></i>
                                    </button>
                                @endif
                            </div>

                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('profile.public', $comment->user->id) }}">
                                    @if($comment->user->avatar)
                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #2c3e50; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                                            {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                        </div>
                                    @endif
                                </a>
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.8rem;">
                                        <a href="{{ route('profile.public', $comment->user->id) }}" style="color: #e9ecef; font-weight: 500; text-decoration: none;">
                                            {{ $comment->user->username }}
                                        </a>
                                        @if($comment->user->role == 'admin')
                                            <span style="background: #e74c3c; color: white; padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.7rem; font-weight: bold;">ADMIN</span>
                                        @endif
                                        <span style="color: #6c757d; font-size: 0.85rem;">• {{ $comment->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div id="comment-body-{{ $comment->id }}" style="color: var(--text-color); line-height: 1.5; font-size: 0.95rem; white-space: pre-wrap;">{{ $comment->body }}</div>

                                    <form id="comment-edit-form-{{ $comment->id }}" action="{{ route('comments.update', $comment->id) }}" method="POST" style="display: none; margin-top: 1rem;">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="body" rows="3" required style="width: 100%; min-height: 80px; background: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; resize: vertical; outline: none; color: var(--text-primary); font-size: 0.95rem; margin-bottom: 0.5rem;">{{ $comment->body }}</textarea>
                                        <div style="text-align: right;">
                                            <button type="button" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem;" onclick="cancelEdit({{ $comment->id }})">Скасувати</button>
                                            <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem; margin-left: 0.5rem;">Зберегти</button>
                                        </div>
                                    </form>

                                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg-dark); padding: 0.2rem 0.5rem; border-radius: 20px; border: 1px solid var(--border-color);">
                                            @php
                                                $userVote = auth()->check() ? $comment->votes->where('user_id', auth()->id())->first() : null;
                                                $voteValue = $userVote ? $userVote->vote : 0;
                                            @endphp
                                            <button id="upvote-btn-{{ $comment->id }}" onclick="voteComment({{ $comment->id }}, 1)" style="background: transparent; border: none; cursor: pointer; color: {{ $voteValue == 1 ? '#2ecc71' : 'var(--text-muted)' }}; display: flex; align-items: center; padding: 0.2rem;">
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                            <span id="rating-val-{{ $comment->id }}" style="font-size: 0.9rem; font-weight: bold; min-width: 20px; text-align: center; color: {{ $comment->votes()->sum('vote') > 0 ? '#2ecc71' : ($comment->votes()->sum('vote') < 0 ? '#e74c3c' : 'var(--text-color)') }};">
                                                {{ $comment->votes()->sum('vote') }}
                                            </span>
                                            <button id="downvote-btn-{{ $comment->id }}" onclick="voteComment({{ $comment->id }}, -1)" style="background: transparent; border: none; cursor: pointer; color: {{ $voteValue == -1 ? '#e74c3c' : 'var(--text-muted)' }}; display: flex; align-items: center; padding: 0.2rem;">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 3rem 0; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <i class="far fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p style="margin: 0;">Ще немає коментарів. Будьте першим!</p>
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

                        const statusBar = document.getElementById('poster-status-bar');
                        if (statusBar) {
                            let barColor = 'transparent';
                            switch(status) {
                                case 'watching': barColor = 'rgba(46, 204, 113, 0.50)'; break;
                                case 'plan_to_watch': barColor = 'rgba(155, 89, 182, 0.50)'; break;
                                case 'completed': barColor = 'rgba(52, 152, 219, 0.50)'; break;
                                case 'on_hold': barColor = 'rgba(241, 196, 15, 0.50)'; break;
                                case 'dropped': barColor = 'rgba(231, 76, 60, 0.50)'; break;
                            }
                            statusBar.style.backgroundColor = barColor;
                            statusBar.innerText = text.toUpperCase();
                            statusBar.style.display = 'block';
                        }
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

        // JS для коментарів
        function toggleCommentMenu(id) {
            const menu = document.getElementById('comment-menu-' + id);
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function editComment(id) {
            document.getElementById('comment-body-' + id).style.display = 'none';
            document.getElementById('comment-edit-form-' + id).style.display = 'block';
            document.getElementById('comment-menu-' + id).style.display = 'none';
        }

        function cancelEdit(id) {
            document.getElementById('comment-body-' + id).style.display = 'block';
            document.getElementById('comment-edit-form-' + id).style.display = 'none';
        }

        function voteComment(id, voteValue) {
            fetch(`/comments/${id}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ vote: voteValue })
            })
            .then(response => {
                if(!response.ok) {
                    if (response.status === 401) {
                        alert('Будь ласка, увійдіть, щоб голосувати.');
                    } else if (response.status === 400) {
                        return response.json().then(data => { alert(data.error); throw new Error(data.error); });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Оновлюємо рейтинг
                document.getElementById('rating-val-' + id).innerText = data.rating;
                
                // Оновлюємо кольори кнопок
                const upBtn = document.getElementById('upvote-btn-' + id);
                const downBtn = document.getElementById('downvote-btn-' + id);
                
                upBtn.style.color = '#6c757d';
                downBtn.style.color = '#6c757d';
                
                if (data.userVote == 1) {
                    upBtn.style.color = '#2ecc71';
                } else if (data.userVote == -1) {
                    downBtn.style.color = '#e74c3c';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener('click', function(e) {
            const dropdowns = document.querySelectorAll('[id^="comment-menu-"]');
            dropdowns.forEach(menu => {
                const id = menu.id.replace('comment-menu-', '');
                if (!menu.contains(e.target) && !e.target.closest(`button[onclick="toggleCommentMenu(${id})"]`)) {
                    menu.style.display = 'none';
                }
            });
        });

        function openReportModal(type, refId) {
            document.getElementById('report-type-input').value = type;
            document.getElementById('report-ref-input').value = refId;
            document.getElementById('reportModal').style.display = 'flex';
        }

        function closeReportModal() {
            document.getElementById('reportModal').style.display = 'none';
            if (document.getElementById('report-desc-input')) {
                document.getElementById('report-desc-input').value = '';
            }
            if (document.getElementById('report-reason-input')) {
                document.getElementById('report-reason-input').selectedIndex = 0;
            }
        }

        let commentToDelete = null;
        function confirmDeleteComment(id) {
            commentToDelete = id;
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }
        function closeDeleteModal() {
            commentToDelete = null;
            document.getElementById('deleteConfirmModal').style.display = 'none';
        }
        function submitDeleteComment() {
            if (commentToDelete) {
                const form = document.getElementById('delete-comment-form-' + commentToDelete);
                if (form) {
                    form.requestSubmit();
                }
            }
        }
    </script>
    
    <!-- Модальне вікно для скарг -->
    <div id="reportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); padding: 1.5rem 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 500px; position: relative; border: 1px solid var(--border-color); box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                <h3 id="report-modal-title" style="margin: 0; font-size: 1.2rem; color: var(--text-primary); font-weight: 600;"><i class="far fa-flag" style="color: #e74c3c; margin-right: 0.5rem;"></i> Поскаржитись</h3>
                <button onclick="closeReportModal()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem; padding: 0;"><i class="fas fa-times"></i></button>
            </div>

            <form action="{{ route('reports.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" id="report-type-input" value="">
                <input type="hidden" name="reference_id" id="report-ref-input" value="">
                
                <div class="form-group" style="margin-bottom: 1.2rem;">
                    <label style="color: var(--text-muted); margin-bottom: 0.5rem; display: block; font-size: 0.9rem;">Причина скарги</label>
                    <select name="reason" id="report-reason-input" class="form-control" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary); width: 100%; padding: 0.6rem 0.8rem; border-radius: 6px; outline: none; appearance: auto; transition: border-color 0.2s; font-size: 0.95rem;" required>
                        <option value="Спам / Реклама">Спам / Реклама</option>
                        <option value="Образа / Ненормативна лексика">Образа / Ненормативна лексика</option>
                        <option value="Неприйнятний контент (18+)">Неприйнятний контент (18+)</option>
                        <option value="Фейковий акаунт">Фейковий акаунт</option>
                        <option value="Помилка (Плеєр/Інфо)">Помилка (Плеєр/Інфо)</option>
                        <option value="Інше">Інше</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: var(--text-muted); margin-bottom: 0.5rem; display: block; font-size: 0.9rem;">Опис</label>
                    <textarea name="description" id="report-desc-input" class="form-control" rows="5" placeholder="Опишіть деталі порушення (необов'язково)..." style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary); width: 100%; padding: 0.8rem; border-radius: 6px; outline: none; resize: none; font-size: 0.95rem;"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn" style="background: transparent; border: none; color: var(--text-muted); font-weight: 500; padding: 0.5rem 1rem;" onclick="closeReportModal()">Скасувати</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem; background: #e74c3c; border-color: #e74c3c; border-radius: 6px; font-weight: 600;">Надіслати скаргу</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальне вікно підтвердження видалення -->
    <div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-md); width: 100%; max-width: 350px; text-align: left; border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--text-primary);">Видалити коментар?</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Цю дію неможливо скасувати.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button type="button" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.9rem; border-color: var(--border-color); color: var(--text-muted);" onclick="closeDeleteModal()">Скасувати</button>
                <button type="button" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem; background: #e74c3c; border-color: #e74c3c;" onclick="submitDeleteComment()">Видалити</button>
            </div>
        </div>
    </div>

    <style>
        .comment-action-btn {
            background: transparent;
            border: 1px solid transparent;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0.5;
        }
        .comment-item:hover .comment-action-btn {
            opacity: 1;
        }
        .comment-action-btn:hover {
            background: var(--bg-dark);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .comment-action-btn.delete-btn:hover {
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.3);
            background: rgba(231, 76, 60, 0.1);
        }
        .comment-action-btn.report-btn:hover {
            color: #e74c3c; /* Згідно макету прапорець червоний/рожевий на скарзі */
            border-color: rgba(231, 76, 60, 0.3);
            background: rgba(231, 76, 60, 0.1);
        }
        .comment-action-btn.edit-btn:hover {
            color: #3498db;
            border-color: rgba(52, 152, 219, 0.3);
            background: rgba(52, 152, 219, 0.1);
        }
    </style>
    @endauth

    <script>
        function initRating() {
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
                        let targetForm = selectedScore === currentScore ? deleteForm : storeForm;
                        if (selectedScore !== currentScore) {
                            scoreInput.value = selectedScore;
                        }
                        
                        let formData = new FormData(targetForm);
                        fetchAndReplace(targetForm.action, '#rating-wrapper', false, targetForm.method || 'POST', formData);
                    });
                });

                starContainer.addEventListener('mouseleave', function() {
                    updateStars(currentScore);
                });
            }
        }

        function fetchAndReplace(url, selector, updateUrl = false, method = 'GET', body = null) {
            let container = document.querySelector(selector);
            if (!container) return Promise.reject('Container not found');

            // Візуальний ефект завантаження
            container.style.transition = 'opacity 0.2s';
            container.style.opacity = '0.5';

            let options = {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            };
            if (body) {
                options.body = body;
            }

            return fetch(url, options)
                .then(response => response.text())
                .then(html => {
                    let doc = new DOMParser().parseFromString(html, 'text/html');
                    let newContent = doc.querySelector(selector);
                    if (newContent) {
                        container.innerHTML = newContent.innerHTML;
                        container.style.opacity = '1';
                        
                        if (updateUrl && method === 'GET') {
                            window.history.pushState({}, '', url);
                        }

                        // Переініціалізація скриптів залежно від блоку
                        if (selector === '#rating-wrapper') {
                            initRating();
                        }
                    } else {
                        container.style.opacity = '1';
                    }
                })
                .catch(err => {
                    console.error('AJAX Error:', err);
                    container.style.opacity = '1';
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            initRating();
        });

        // Перехоплення кліків на посилання
        document.addEventListener('click', function(e) {
            let link = e.target.closest('a');
            if (link && link.href) {
                try {
                    let url = new URL(link.href, window.location.origin);
                    if (url.pathname === window.location.pathname) {
                        if (url.hash === '#player-section') {
                            e.preventDefault();
                            fetchAndReplace(link.href, '#player-section', true);
                        } else if (url.hash === '#comments-section') {
                            e.preventDefault();
                            fetchAndReplace(link.href, '#comments-section', false);
                        }
                    }
                } catch (err) {}
            }
        });

        // Перехоплення відправки форм
        document.addEventListener('submit', function(e) {
            let form = e.target;
            if (!form.action) return;

            try {
                let url = new URL(form.action, window.location.origin);
                
                let isRating = url.pathname.includes('/ratings') || form.id === 'rating-store-form' || form.id === 'rating-delete-form';
                let isComment = url.pathname.includes('/comments') || form.id.includes('delete-comment-form');
                
                if (isRating || isComment) {
                    e.preventDefault();
                    
                    let selector = isRating ? '#rating-wrapper' : '#comments-section';
                    let formData = new FormData(form);
                    
                    fetchAndReplace(form.action, selector, false, form.method || 'POST', formData).then(() => {
                        // Якщо це форма видалення коментаря, треба сховати модалку
                        if (form.id.includes('delete-comment-form') && typeof closeDeleteModal === 'function') {
                            closeDeleteModal();
                        }
                    });
                }
            } catch (err) {}
        });

        // Підтримка кнопок "назад/вперед" у браузері для епізодів
        window.addEventListener('popstate', function(e) {
            fetchAndReplace(window.location.href, '#player-section', false);
        });
    </script>
@push('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emojiBtn = document.getElementById('emoji-picker-btn');
        if (emojiBtn) {
            let pickerContainer = null;
            const textarea = document.querySelector('textarea[name="body"]');

            emojiBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!pickerContainer) {
                    pickerContainer = document.createElement('div');
                    pickerContainer.style.position = 'absolute';
                    pickerContainer.style.bottom = 'calc(100% + 10px)';
                    pickerContainer.style.left = '0';
                    pickerContainer.style.zIndex = '1000';
                    pickerContainer.style.boxShadow = '0 10px 25px rgba(0,0,0,0.5)';
                    pickerContainer.style.borderRadius = '8px';
                    pickerContainer.style.overflow = 'hidden';
                    
                    const picker = document.createElement('emoji-picker');
                    pickerContainer.appendChild(picker);
                    emojiBtn.parentElement.appendChild(pickerContainer);

                    const style = document.createElement('style');
                    style.textContent = `
                        emoji-picker {
                            --background: var(--bg-card);
                            --border-color: var(--border-color);
                            --input-border-color: var(--border-color);
                            --input-font-color: var(--text-primary);
                            --button-hover-background: rgba(255,255,255,0.1);
                            --indicator-color: var(--accent-color);
                            --category-font-color: var(--text-muted);
                        }
                    `;
                    document.head.appendChild(style);

                    picker.addEventListener('emoji-click', event => {
                        const cursorPosition = textarea.selectionStart;
                        const textBefore = textarea.value.substring(0, cursorPosition);
                        const textAfter = textarea.value.substring(cursorPosition);
                        textarea.value = textBefore + event.detail.unicode + textAfter;
                        textarea.selectionStart = textarea.selectionEnd = cursorPosition + event.detail.unicode.length;
                        textarea.focus();
                        
                        textarea.style.height = '';
                        textarea.style.height = textarea.scrollHeight + 'px';
                    });
                    
                    document.addEventListener('click', function(event) {
                        if (pickerContainer && !pickerContainer.contains(event.target) && !emojiBtn.contains(event.target)) {
                            pickerContainer.style.display = 'none';
                        }
                    });
                } else {
                    pickerContainer.style.display = pickerContainer.style.display === 'none' ? 'block' : 'none';
                }
            });
        }
    });
    function toggleExpandText(btn) {
        const textSpan = btn.previousElementSibling;
        const isExpanded = btn.textContent === 'згорнути';
        if (isExpanded) {
            textSpan.textContent = textSpan.getAttribute('data-short');
            btn.textContent = 'розгорнути';
        } else {
            textSpan.textContent = textSpan.getAttribute('data-full');
            btn.textContent = 'згорнути';
        }
    }
</script>
<style>
    .btn-expand-title {
        background: rgba(138, 43, 226, 0.1);
        border: 1px solid rgba(138, 43, 226, 0.3);
        color: var(--accent-color);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.1rem 0.6rem;
        border-radius: 12px;
        margin-left: 0.5rem;
        text-decoration: none;
        vertical-align: middle;
        transition: all 0.2s;
        display: inline-block;
        line-height: 1.5;
        position: relative;
        top: -2px;
    }
    .btn-expand-title:hover {
        background: rgba(138, 43, 226, 0.2);
    }
</style>
@endpush
@endsection
