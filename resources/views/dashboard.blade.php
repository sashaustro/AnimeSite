@extends('layouts.public')

@section('title', 'Мій Кабінет - AniHub')

@section('content')
<div class="container">
    <h2 class="section-title">Мій Кабінет</h2>

    <!-- Статистика -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">{{ $stats['watching'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Переглядаю</div>
        </div>
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: #3498db;">{{ $stats['plan_to_watch'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">В планах</div>
        </div>
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: #2ecc71;">{{ $stats['completed'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Переглянуто</div>
        </div>
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: #f1c40f;">{{ $stats['on_hold'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Відкладено</div>
        </div>
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: #e74c3c;">{{ $stats['dropped'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Кинуто</div>
        </div>
        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 2rem; font-weight: bold; color: #e84393;">{{ $stats['favorites'] ?? 0 }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Ізбране</div>
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
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem;">
                                @if($history->anime)
                                    <a href="{{ route('anime.show', ['anime' => $history->anime->id, 'ep' => $history->episode_id]) }}">
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
</div>
@endsection
