@extends('layouts.admin')

@section('title', 'Список Аніме')
@section('page_title', 'Список Аніме')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Всі аніме в базі</h3>
        <div class="card-tools">
            <a href="{{ route('anime.create') }}" class="btn btn-sm btn-primary">Додати нове</a>
        </div>
    </div>
    
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Постер</th>
                    <th>Назва</th>
                    <th>Рік / Статус</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animes as $anime)
                    <tr>
                        <td>{{ $anime->id }}</td>
                        <td>
                            @if($anime->image)
                                <img src="{{ asset('storage/' . $anime->image) }}" alt="poster" width="50">
                            @else
                                <span class="text-muted">Немає</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('anime.show', $anime->id) }}" target="_blank">
                                <strong>{{ $anime->title }}</strong>
                            </a>
                        </td>
                        <td>
                            {{ $anime->year ?? '—' }} <br>
                            <small class="text-muted">{{ $anime->status }}</small>
                        </td>
                        <td>
                            <a href="{{ route('anime.edit', $anime->id) }}" class="btn btn-sm btn-info" title="Редагувати"><i class="fas fa-edit"></i></a>
                            
                            <form action="{{ route('anime.destroy', $anime->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Видалити це аніме?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Видалити"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Немає жодного аніме в базі</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($animes->hasPages())
        <div class="card-footer">
            {{ $animes->links() }}
        </div>
    @endif
</div>
@endsection
