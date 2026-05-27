@extends('layouts.admin')

@section('title', 'Редагувати Аніме')
@section('page_title', 'Редагувати: ' . $anime->title)

@section('content')
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Редагування інформації</h3>
            <div class="card-tools">
                <a href="{{ route('anime.show', $anime->id) }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-external-link-alt"></i> Переглянути на сайті
                </a>
            </div>
        </div>
        
        <form action="{{ route('anime.update', $anime->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Назва аніме</label>
                            <input type="text" class="form-control" name="title" value="{{ $anime->title }}" required>
                        </div>

                        <div class="form-group">
                            <label>Опис</label>
                            <textarea name="description" class="form-control" rows="5">{{ $anime->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Постер (Зображення)</label><br>
                            @if($anime->image)
                                <img src="{{ asset('storage/' . $anime->image) }}" width="100" style="margin-bottom: 10px; border-radius: 5px;"><br>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" accept="image/*">
                                    <label class="custom-file-label">Оберіть новий файл (або залиште порожнім)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Рік випуску</label>
                            <input type="number" class="form-control" name="year" value="{{ $anime->year }}" min="1900" max="2100">
                        </div>
                        <div class="form-group">
                            <label>Формат</label>
                            <input type="text" list="formats" class="form-control" name="format" value="{{ $anime->format }}">
                            <datalist id="formats">
                                <option value="Серіал">
                                <option value="Фільм">
                                <option value="OVA">
                                <option value="ONA">
                                <option value="Спешл">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Країна</label>
                            <input type="text" class="form-control" name="country" value="{{ $anime->country }}">
                        </div>
                        <div class="form-group">
                            <label>Студія</label>
                            <input type="text" list="studios" class="form-control" name="studio" value="{{ $anime->studio }}">
                            <datalist id="studios">
                                <option value="Mappa">
                                <option value="Ufotable">
                                <option value="Kyoto Animation">
                                <option value="Madhouse">
                                <option value="Bones">
                                <option value="A-1 Pictures">
                                <option value="CloverWorks">
                                <option value="УкрАніме">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Озвучка</label>
                            <input type="text" list="voices" class="form-control" name="voice_acting" value="{{ $anime->voice_acting }}">
                            <datalist id="voices">
                                <option value="Оригінал">
                                <option value="FanVoxUA">
                                <option value="Amanogawa">
                                <option value="Inari">
                                <option value="Студійна Толока">
                                <option value="AniUA">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Статус</label>
                            <select name="status" class="form-control">
                                <option value="ongoing" {{ $anime->status == 'ongoing' ? 'selected' : '' }}>Онгоїнг (Ongoing)</option>
                                <option value="completed" {{ $anime->status == 'completed' ? 'selected' : '' }}>Завершено (Completed)</option>
                                <option value="announced" {{ $anime->status == 'announced' ? 'selected' : '' }}>Анонс (Announced)</option>
                                <option value="paused" {{ $anime->status == 'paused' ? 'selected' : '' }}>Призупинено (Paused)</option>
                                <option value="cancelled" {{ $anime->status == 'cancelled' ? 'selected' : '' }}>Скасовано (Cancelled)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Зберегти зміни</button>
            </div>
        </form>
    </div>

<div class="card card-info mt-4">
    <div class="card-header">
        <h3 class="card-title">Епізоди (Відео)</h3>
        <div class="card-tools">
            <a href="{{ route('episodes.create', $anime->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Додати епізод
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Серія</th>
                    <th>Назва</th>
                    <th>Посилання</th>
                    <th style="width: 100px">Дії</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anime->episodes as $episode)
                    <tr>
                        <td>{{ $episode->episode_number }}</td>
                        <td>{{ $episode->title ?: '—' }}</td>
                        <td><a href="{{ $episode->video_url }}" target="_blank">Відкрити відео</a></td>
                        <td>
                            <form action="{{ route('episodes.destroy', $episode->id) }}" method="POST" style="display: flex; gap: 5px;">
                                <a href="{{ route('episodes.edit', $episode->id) }}" class="btn btn-sm btn-info" title="Редагувати">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Видалити цей епізод?')" title="Видалити">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">До цього аніме ще не додано жодного епізоду.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection