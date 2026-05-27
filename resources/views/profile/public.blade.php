@extends('layouts.public')

@section('title', 'Профіль користувача ' . $user->username . ' - AniHub')

@section('content')
<div class="container">
    <a href="{{ url()->previous() }}" style="color: var(--text-muted); text-decoration: none; display: inline-block; margin-bottom: 1.5rem; font-size: 0.9rem;">
        &larr; Назад
    </a>

    <!-- Шапка профілю -->
    <div class="profile-header" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 2.5rem; text-align: center;">
        <!-- Avatar -->
        <div style="position: relative; width: 120px; height: 120px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center;">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
            @else
                <div style="width: 100%; height: 100%; border-radius: 50%; background: var(--bg-card); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--text-muted);">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>

        <!-- Username & Status -->
        <div style="display: flex; justify-content: center; margin-bottom: 0.5rem;">
            <div style="position: relative; display: inline-flex; align-items: center;">
                <h2 style="margin: 0; font-size: 1.5rem;">{{ $user->username }}</h2>
                @if($user->role == 'admin')
                    <span style="position: absolute; left: 100%; margin-left: 0.5rem; background: #e74c3c; color: white; font-size: 0.7rem; padding: 0.1rem 0.4rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); display: inline-flex; align-items: center; justify-content: center;">Admin</span>
                @endif
            </div>
        </div>
        
        <div style="color: var(--text-primary); margin-bottom: 0.5rem;">
            {{ $user->status_text ?: 'Статус не встановлено' }}
        </div>
        
        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">
            На сайті з {{ $user->created_at->format('d.m.Y') }}
        </div>

        <!-- Stats Row -->
        <div style="display: flex; justify-content: center; gap: 2rem;">
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">{{ $user->comments_count }}</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Коментарів</div>
            </div>
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">{{ $collections->count() }}</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Колекцій</div>
            </div>
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">0</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Друзів</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Статистика -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        
        <!-- Загальна статистика (Donut Chart) -->
        <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1; min-width: 150px;">
                <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.2rem; color: var(--text-primary);">Статистика <i class="fas fa-info-circle" style="color: var(--text-muted); font-size: 0.9rem;"></i></h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.95rem;">
                    <div style="display: flex; align-items: center; gap: 0.7rem;">
                        <div style="width: 14px; height: 14px; border-radius: 4px; background-color: #2ecc71;"></div>
                        <span style="color: var(--text-muted); width: 110px;">Переглядаю</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['watching'] ?? 0 }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.7rem;">
                        <div style="width: 14px; height: 14px; border-radius: 4px; background-color: #9b59b6;"></div>
                        <span style="color: var(--text-muted); width: 110px;">В планах</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['planned'] ?? 0 }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.7rem;">
                        <div style="width: 14px; height: 14px; border-radius: 4px; background-color: #3498db;"></div>
                        <span style="color: var(--text-muted); width: 110px;">Переглянуто</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['completed'] ?? 0 }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.7rem;">
                        <div style="width: 14px; height: 14px; border-radius: 4px; background-color: #f1c40f;"></div>
                        <span style="color: var(--text-muted); width: 110px;">Відкладено</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['on_hold'] ?? 0 }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.7rem;">
                        <div style="width: 14px; height: 14px; border-radius: 4px; background-color: #e74c3c;"></div>
                        <span style="color: var(--text-muted); width: 110px;">Кинуто</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['dropped'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
            <div style="width: 180px; height: 180px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Динаміка перегляду (Bar Chart) -->
        <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.2rem; color: var(--text-primary);">Динаміка перегляду серій <i class="fas fa-info-circle" style="color: var(--text-muted); font-size: 0.9rem;"></i></h3>
            <div style="height: 180px; position: relative;">
                <canvas id="dynamicsChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Історія переглядів -->
    <h3 style="margin-bottom: 1rem;">Останні перегляди</h3>
    <div style="margin-bottom: 2rem;">
        @if(isset($watchHistory) && $watchHistory->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                @foreach($watchHistory as $history)
                    <div style="display: flex; background: var(--bg-card); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                        @if($history->anime && $history->anime->image)
                            <img src="{{ asset('storage/' . $history->anime->image) }}" style="width: 80px; object-fit: cover;" alt="poster">
                        @else
                            <div style="width: 80px; background: #333; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">Немає</div>
                        @endif
                        
                        <div style="padding: 1rem; flex: 1;">
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem;">
                                @if($history->anime)
                                    <a href="{{ route('anime.show', ['anime' => $history->anime->id, 'ep' => $history->episode_id]) }}" style="color: var(--text-primary); text-decoration: none;">
                                        {{ $history->anime->title }}
                                    </a>
                                @else
                                    Аніме видалено
                                @endif
                            </h4>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">
                                @if($history->episode)
                                    Серія {{ $history->episode->episode_number }}
                                    @if($history->episode->title) - {{ $history->episode->title }} @endif
                                @endif
                            </p>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                                Переглянуто: {{ \Carbon\Carbon::parse($history->watched_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); text-align: center; color: var(--text-muted); border: 1px solid var(--border-color);">
                Користувач ще не переглянув жодного епізоду.
            </div>
        @endif
    </div>
    
    <script>
        // Status Chart (Donut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Переглядаю', 'В планах', 'Переглянуто', 'Відкладено', 'Кинуто'],
                datasets: [{
                    data: [
                        {{ $stats['watching'] ?? 0 }}, 
                        {{ $stats['planned'] ?? 0 }}, 
                        {{ $stats['completed'] ?? 0 }}, 
                        {{ $stats['on_hold'] ?? 0 }}, 
                        {{ $stats['dropped'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#2ecc71', // Переглядаю (greenish)
                        '#9b59b6', // В планах (purple)
                        '#3498db', // Переглянуто (blue)
                        '#f1c40f', // Відкладено (yellow)
                        '#e74c3c'  // Кинуто (red)
                    ],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28, 30, 45, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2d3748',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true
                    }
                }
            }
        });

        // Dynamics Chart (Bar)
        const ctxDynamics = document.getElementById('dynamicsChart').getContext('2d');
        const dynamicsData = {!! json_encode($watchDynamics['data'] ?? [0,0,0,0,0,0,0]) !!};
        const dynamicsLabels = {!! json_encode($watchDynamics['labels'] ?? ['','','','','','','']) !!};
        
        new Chart(ctxDynamics, {
            type: 'bar',
            data: {
                labels: dynamicsLabels,
                datasets: [{
                    label: 'Переглянуто серій',
                    data: dynamicsData,
                    backgroundColor: '#e74c3c',
                    borderRadius: 4,
                    barThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false, drawBorder: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28, 30, 45, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2d3748',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            title: function() { return ''; },
                            label: function(context) {
                                return context.raw + ' серій переглянуто';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Колекції -->
    @if($collections->count() > 0)
        <h3 style="margin-bottom: 1rem;">Колекції користувача ({{ $collections->count() }})</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            @foreach($collections as $collection)
                <div style="background: var(--bg-card); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                    <div style="padding: 1.5rem;">
                        <h4 style="margin: 0 0 0.5rem 0; font-size: 1.2rem; color: var(--text-primary);">{{ $collection->name }}</h4>
                        <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                            Вміщує {{ $collection->animes_count }} аніме
                        </div>
                        @if($collection->animes_count > 0)
                            <div style="display: flex; gap: 0.5rem; overflow: hidden; height: 60px;">
                                @foreach($collection->animes->take(4) as $anime)
                                    @if($anime->image)
                                        <img src="{{ asset('storage/' . $anime->image) }}" alt="poster" style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
