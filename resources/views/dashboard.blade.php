@extends('layouts.public')

@section('title', 'Мій Кабінет - AniHub')

@section('content')
<div class="container">
    <div class="breadcrumb" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">AniHub</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Кабінет</span>
    </div>
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

            <button onclick="document.getElementById('avatar-upload').click()" style="position: absolute; bottom: 5px; right: 5px; background: var(--accent-color); color: white; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.3);" title="Змінити аватар">
                <i class="fas fa-camera"></i>
            </button>
            <form id="avatar-form" action="{{ route('cabinet.profile.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                @method('PUT')
                <input type="file" name="avatar" id="avatar-upload" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
            </form>
        </div>

        <!-- Username & Status -->
        <div style="display: flex; justify-content: center; margin-bottom: 0.5rem;">
            <div style="position: relative; display: inline-flex; align-items: center; transform: translateX(-12px);">
                <h2 style="margin: 0; font-size: 1.5rem;">{{ $user->username }}</h2>
                <span style="position: absolute; left: 100%; margin-left: 0.5rem; background: var(--bg-card); color: var(--text-muted); font-size: 0.8rem; padding: 0.1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); display: inline-flex; align-items: center; justify-content: center;">1</span>
            </div>
        </div>

        <div style="color: var(--text-primary); margin-bottom: 0.5rem; cursor: pointer;" onclick="document.getElementById('statusModal').style.display='flex'">
            {{ $user->profile_status ?: 'Статус не встановлено' }}
        </div>

        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span>онлайн <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-left: 2px;"></i></span>
            
            <div style="display: flex; align-items: center; gap: 0.3rem; background: var(--bg-dark); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); cursor: pointer;" onclick="navigator.clipboard.writeText('{{ $user->id }}'); const icon = this.querySelector('.fa-copy'); icon.classList.remove('far', 'fa-copy'); icon.classList.add('fas', 'fa-check', 'text-success'); setTimeout(() => { icon.classList.remove('fas', 'fa-check', 'text-success'); icon.classList.add('far', 'fa-copy'); }, 2000);" title="Натисніть, щоб скопіювати ваш ID">
                <span style="font-weight: bold;">ID: {{ $user->id }}</span>
                <i class="far fa-copy" style="font-size: 0.75rem;"></i>
            </div>
            <div style="display: flex; align-items: center; gap: 0.8rem;" title="Кількість вподобань та дизлайків на ваших коментарях">
                <div style="display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-chevron-up" style="color: #2ecc71;"></i>
                    <span style="font-weight: bold; color: #2ecc71;">{{ $user->positive_votes }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-chevron-down" style="color: #e74c3c;"></i>
                    <span style="font-weight: bold; color: #e74c3c;">{{ $user->negative_votes }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem;">
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">{{ $user->comments->count() }}</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Коментарів</div>
            </div>
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">{{ $user->collections->count() }}</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Колекцій</div>
            </div>
            <div style="text-align: center; width: 90px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--accent-color);">0</div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Друзів</div>
            </div>
        </div>

        <button class="btn btn-outline" style="width: 100%; max-width: 300px; color: var(--accent-color); border-color: var(--border-color); font-weight: normal; border-radius: 20px;" onclick="document.getElementById('btn-settings').click(); document.getElementById('settings-tab').scrollIntoView({behavior: 'smooth'})">Редагувати</button>
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
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $stats['plan_to_watch'] ?? 0 }}</strong>
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

    <!-- Таби (Навігація) -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; align-items: center;">
        <button onclick="document.getElementById('history-tab').style.display='block'; document.getElementById('settings-tab').style.display='none'; this.style.color='var(--accent-color)'; document.getElementById('btn-settings').style.color='var(--text-primary)';" id="btn-history" style="background: none; border: none; color: var(--accent-color); font-size: 1.1rem; font-weight: 600; cursor: pointer;">Історія переглядів</button>
        <button onclick="document.getElementById('settings-tab').style.display='block'; document.getElementById('history-tab').style.display='none'; this.style.color='var(--accent-color)'; document.getElementById('btn-history').style.color='var(--text-primary)';" id="btn-settings" style="background: none; border: none; color: var(--text-primary); font-size: 1.1rem; font-weight: 600; cursor: pointer;">Налаштування профілю</button>

        <div style="margin-left: auto; display: flex; gap: 1rem;">
            <a href="{{ route('cabinet.lists') }}" class="btn btn-outline" style="padding: 0.4rem 1rem;"><i class="fas fa-list"></i> Мої списки</a>
            <a href="{{ route('cabinet.collections') }}" class="btn btn-outline" style="padding: 0.4rem 1rem;"><i class="fas fa-folder"></i> Колекції</a>
        </div>
    </div>

    <!-- Вкладка: Історія -->
    <div id="history-tab" class="details-main" style="margin-bottom: 2rem;">
        @if(isset($watchHistory) && $watchHistory->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                @foreach($watchHistory as $history)
                    <div style="display: flex; background: var(--bg-dark); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                        @if($history->anime && $history->anime->image)
                            <img src="{{ asset('storage/' . $history->anime->image) }}" style="width: 80px; object-fit: cover;" alt="poster">
                        @else
                            <div style="width: 80px; background: #333; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">Немає</div>
                        @endif

                        <div style="padding: 1rem; flex: 1;">
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;" title="{{ $history->anime->title ?? '' }}">
                                @if($history->anime)
                                    <a href="{{ route('anime.show', ['anime' => $history->anime->id, 'ep' => $history->episode_id]) }}" style="color: inherit; text-decoration: none;">
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
            <p style="color: var(--text-muted);">Ви ще не переглянули жодного епізоду.</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 1rem;">Перейти до каталогу</a>
        @endif
    </div>

    <!-- Вкладка: Налаштування -->
    <div id="settings-tab" style="display: none; margin-bottom: 2rem; max-width: 600px;">
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
            <form action="{{ route('cabinet.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Ім'я (як до вас звертатись)</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Нікнейм (для логіну)</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', auth()->user()->username) }}" required>
                    @error('username')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Вік</label>
                        <input type="number" name="age" class="form-control" value="{{ old('age', auth()->user()->age) }}">
                        @error('age')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr style="border-color: var(--border-color); margin: 2rem 0;">
                <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Зміна паролю (залиште порожнім, якщо не хочете змінювати)</h4>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Новий пароль</label>
                    <input type="password" name="password" class="form-control">
                    @error('password')<div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label">Підтвердження нового паролю</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">Зберегти зміни</button>
            </form>
        </div>
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
                        {{ $stats['plan_to_watch'] ?? 0 }},
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
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });

        // Dynamics Chart (Bar)
        const ctxDynamics = document.getElementById('dynamicsChart').getContext('2d');
        new Chart(ctxDynamics, {
            type: 'bar',
            data: {
                labels: @json($watchDynamics['labels']),
                datasets: [{
                    label: 'Переглянуто серій',
                    data: @json($watchDynamics['data']),
                    backgroundColor: '#ef4444',
                    borderRadius: 4,
                    barThickness: 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        display: false,
                        beginAtZero: true,
                        max: Math.max(...@json($watchDynamics['data'])) + 2
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 11 }
                        }
                    }
                },
                layout: {
                    padding: { top: 20 }
                }
            },
            plugins: [{
                id: 'topLabels',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    const { ctx, data } = chart;
                    ctx.save();
                    chart.getDatasetMeta(0).data.forEach((datapoint, index) => {
                        const value = data.datasets[0].data[index];
                        if (value > 0) {
                            ctx.font = 'bold 13px sans-serif';
                            ctx.fillStyle = '#9ca3af';
                            ctx.textAlign = 'center';
                            ctx.fillText(value, datapoint.x, datapoint.y - 8);
                        }
                    });
                }
            }]
        });
    </script>
    <div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-lg); width: 100%; max-width: 400px; position: relative;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--text-primary);">Змінити статус</h3>
            <form action="{{ route('cabinet.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="profile_status" rows="3" class="form-control" style="width: 100%; resize: none; margin-bottom: 0.5rem;" maxlength="80" placeholder="Ваш статус..." oninput="document.getElementById('status-counter').innerText = this.value.length + '/80'">{{ $user->profile_status }}</textarea>
                <div style="text-align: right; color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1rem;" id="status-counter">{{ mb_strlen($user->profile_status ?: '') }}/80</div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" class="btn btn-outline" style="flex: 1; border-radius: 20px; color: var(--accent-color); border-color: var(--border-color);" onclick="document.getElementById('statusModal').style.display='none'">Скасувати</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1; border-radius: 20px; background-color: #ef4444; border-color: #ef4444; color: white;">Зберегти</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
