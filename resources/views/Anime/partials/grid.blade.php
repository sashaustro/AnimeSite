<div class="anime-grid">
    @forelse($animes as $item)
        <a href="{{ route('anime.show', $item->id) }}" class="anime-card">
            <div style="position: relative;">
                @if($item->updated_at && $item->updated_at >= \Carbon\Carbon::now()->subDays(3))
                    <span class="badge">NEW</span>
                @endif
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="anime-poster" loading="lazy">
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
            @php
                $hasFilters = request()->hasAny(['search', 'genres', 'studios', 'statuses', 'countries', 'year_min', 'year_max']);
            @endphp
            @if($hasFilters)
                <h2>За вашими критеріями нічого не знайдено...</h2>
            @else
                <h2>На даний момент нічого ще не додано.</h2>
            @endif
        </div>
    @endforelse
</div>

@if($animes->hasMorePages())
    <div class="infinite-scroll-trigger" data-next-page="{{ $animes->nextPageUrl() }}" style="text-align: center; padding: 2rem; color: var(--accent-color);">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
    </div>
@endif

<div style="display: none;" class="pagination-wrapper">
    {{ $animes->links() }}
</div>
