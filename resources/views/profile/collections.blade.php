@extends('layouts.public')

@section('title', 'Мої колекції - AniHub')

@section('content')
<style>
    .custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        background: var(--bg-dark);
        border: 2px solid var(--border-color);
        border-radius: 4px;
        display: inline-block;
        position: relative;
        cursor: pointer;
        margin: 0;
        transition: all 0.2s ease;
    }
    .custom-checkbox:checked {
        background: var(--primary-color, #a855f7);
        border-color: var(--primary-color, #a855f7);
    }
    .custom-checkbox:checked::after {
        content: '';
        position: absolute;
        top: 1px;
        left: 5px;
        width: 4px;
        height: 9px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    .custom-checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        user-select: none;
        color: var(--text-primary);
        margin: 0;
        padding: 0.5rem 0;
    }
</style>
<div class="container">
    <div class="breadcrumb" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">AniHub</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <a href="{{ route('cabinet') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">Кабінет</a>
        <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Мої колекції</span>
    </div>
    <div style="display: flex; align-items: center; margin-bottom: 2rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Мої колекції</h2>
        <a href="{{ route('cabinet') }}" class="btn btn-outline" style="margin-left: auto;">В кабінет</a>
    </div>

    @if(session('success'))
        <div style="background: #2ecc71; color: #fff; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-bottom: 2rem;">
        <h3 style="margin-top: 0; margin-bottom: 1rem;">Створити нову колекцію</h3>
        <form action="{{ route('collections.store') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <input type="text" name="name" class="form-control" placeholder="Назва колекції..." required>
                </div>
                <div style="flex: 2; min-width: 250px;">
                    <input type="text" name="description" class="form-control" placeholder="Короткий опис (необов'язково)">
                </div>
                <div>
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="is_public" value="1" class="custom-checkbox" checked> Публічна
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; min-height: 40px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"><i class="fas fa-plus"></i> Створити</button>
            </div>
        </form>
    </div>

    @if($collections->count() > 0)
        <div style="display: grid; gap: 1.5rem;">
            @foreach($collections as $collection)
                <div style="background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden;">
                    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                        <div style="flex: 1; min-width: 300px;">
                            <div id="view-info-{{ $collection->id }}">
                                <h3 style="margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                    {{ $collection->name }}
                                    @if(!$collection->is_public)
                                        <span style="font-size: 0.7rem; background: #e74c3c; color: white; padding: 2px 6px; border-radius: 10px;">Приватна</span>
                                    @else
                                        <span style="font-size: 0.7rem; background: #3498db; color: white; padding: 2px 6px; border-radius: 10px;">Публічна</span>
                                    @endif
                                </h3>
                                @if($collection->description)
                                    <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">{{ $collection->description }}</p>
                                @endif
                            </div>
                            
                            <form id="edit-form-{{ $collection->id }}" action="{{ route('collections.update', $collection->id) }}" method="POST" style="display: none; margin-top: 0.5rem;">
                                @csrf
                                @method('PUT')
                                <div style="display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;">
                                    <div style="flex: 1; min-width: 200px;">
                                        <input type="text" name="name" class="form-control" value="{{ $collection->name }}" required>
                                    </div>
                                    <div style="flex: 2; min-width: 200px;">
                                        <input type="text" name="description" class="form-control" value="{{ $collection->description }}" placeholder="Опис">
                                    </div>
                                    <div>
                                        <label class="custom-checkbox-label">
                                            <input type="checkbox" name="is_public" value="1" class="custom-checkbox" {{ $collection->is_public ? 'checked' : '' }}> Публічна
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.2rem; min-height: 40px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"><i class="fas fa-save"></i> Зберегти</button>
                                    <button type="button" class="btn btn-outline" style="padding: 0.5rem 1.2rem; min-height: 40px; display: flex; align-items: center; justify-content: center;" onclick="document.getElementById('edit-form-{{ $collection->id }}').style.display='none'; document.getElementById('view-info-{{ $collection->id }}').style.display='block'; document.getElementById('actions-{{ $collection->id }}').style.display='block';">Скасувати</button>
                                </div>
                            </form>
                        </div>
                        
                        <div id="actions-{{ $collection->id }}">
                            <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цю колекцію? Всі аніме в ній залишаться на сайті.');" style="display: flex; gap: 0.5rem;">
                                <button type="button" class="btn btn-outline" style="color: #3498db; border-color: #3498db; padding: 0.4rem 1rem;" onclick="document.getElementById('edit-form-{{ $collection->id }}').style.display='block'; document.getElementById('view-info-{{ $collection->id }}').style.display='none'; document.getElementById('actions-{{ $collection->id }}').style.display='none';">
                                    <i class="fas fa-edit"></i> Редагувати
                                </button>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="color: #e74c3c; border-color: #e74c3c; padding: 0.4rem 1rem;">
                                    <i class="fas fa-trash"></i> Видалити
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div style="padding: 1.5rem; background: var(--bg-dark);">
                        @if($collection->animes->count() > 0)
                            <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
                                @foreach($collection->animes as $anime)
                                    <a href="{{ route('anime.show', $anime->id) }}" style="display: block; width: 120px; flex-shrink: 0;">
                                        @if($anime->image)
                                            <img src="{{ asset('storage/' . $anime->image) }}" alt="poster" style="width: 100%; aspect-ratio: 2/3; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 0.5rem;">
                                        @else
                                            <div style="width: 100%; aspect-ratio: 2/3; background: #333; display: flex; align-items: center; justify-content: center; color: #666; border-radius: var(--radius-md); margin-bottom: 0.5rem;">Немає</div>
                                        @endif
                                        <div style="font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center;">{{ $anime->title }}</div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">В цій колекції ще немає аніме. Відкрийте будь-яке аніме та натисніть "В колекцію".</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-muted);">У вас ще немає колекцій</h3>
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Створіть свою першу колекцію вище, щоб зручно групувати аніме.</p>
        </div>
    @endif
</div>
@endsection
