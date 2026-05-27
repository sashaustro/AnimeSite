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
                            <textarea name="description" class="form-control" rows="5" style="min-height: 120px; resize: vertical;">{{ $anime->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Постер (Зображення)</label><br>
                            @if($anime->image)
                                <img src="{{ str_starts_with($anime->image, 'http') ? $anime->image : asset('storage/' . $anime->image) }}" width="100" style="margin-bottom: 10px; border-radius: 5px;"><br>
                            @endif
                            <div class="input-group mb-2">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" accept="image/*">
                                    <label class="custom-file-label">Оберіть новий файл (або залиште порожнім)</label>
                                </div>
                            </div>
                            <small class="text-muted d-block mb-1">АБО вкажіть URL зображення:</small>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Рік випуску</label>
                            <select name="year" class="form-control">
                                <option value="">Невідомо</option>
                                @php $currentYear = date('Y'); @endphp

                                <option value="{{ $currentYear }}" {{ $anime->year == $currentYear ? 'selected' : '' }}>{{ $currentYear }}</option>

                                <optgroup label="Анонси (Майбутні роки)">
                                    @for($y = $currentYear + 1; $y <= $currentYear + 5; $y++)
                                        <option value="{{ $y }}" {{ $anime->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </optgroup>

                                <optgroup label="Минулі роки">
                                    @for($y = $currentYear - 1; $y >= 1950; $y--)
                                        <option value="{{ $y }}" {{ $anime->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Формат</label>
                            <input type="text" list="formats" class="form-control" name="format" value="{{ $anime->format ?? 'Невідомо' }}">
                            <datalist id="formats">
                                <option value="Невідомо">
                                <option value="TV Серіал">
                                <option value="Фільм">
                                <option value="OVA">
                                <option value="ONA">
                                <option value="Спешл">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Країна</label>
                            <input type="text" list="countries" class="form-control" name="country" value="{{ $anime->country ?? 'Невідомо' }}">
                            <datalist id="countries">
                                <option value="Невідомо">
                                <option value="Японія">
                                <option value="Південна Корея">
                                <option value="Китай">
                                <option value="США">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Студія</label>
                            <input type="text" list="studios" class="form-control" name="studio" value="{{ $anime->studio ?? 'Невідомо' }}">
                            <datalist id="studios">
                                <option value="Невідомо">
                                <option value="Mappa">
                                <option value="Ufotable">
                                <option value="Kyoto Animation">
                                <option value="Madhouse">
                                <option value="Bones">
                                <option value="A-1 Pictures">
                                <option value="CloverWorks">
                                <option value="Wit Studio">
                                <option value="Pierrot">
                                <option value="Sunrise">
                                <option value="Toei Animation">
                                <option value="J.C.Staff">
                                <option value="УкрАніме">
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
                        <div class="form-group">
                            <label>Жанри</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left d-flex justify-content-between align-items-center" type="button" id="genresDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #fff; color: #495057; border-color: #ced4da;">
                                    <span id="selectedGenresText">Виберіть жанри...</span>
                                </button>
                                <div class="dropdown-menu w-100 p-3 shadow" aria-labelledby="genresDropdown" style="max-height: 250px; overflow-y: auto;" onclick="event.stopPropagation()">
                                    <div class="row">
                                        @php
                                            $animeGenres = $anime->genres->pluck('id')->toArray();
                                        @endphp
                                        @foreach($genres as $genre)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input genre-checkbox" type="checkbox" name="genres[]" id="genre_{{ $genre->id }}" value="{{ $genre->id }}" data-name="{{ $genre->name }}" {{ in_array($genre->id, $animeGenres) ? 'checked' : '' }}>
                                                <label for="genre_{{ $genre->id }}" class="custom-control-label font-weight-normal cursor-pointer" style="cursor: pointer;">{{ $genre->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
        <div class="card-tools d-flex align-items-center">
            <input type="text" id="episodeSearch" class="form-control form-control-sm mr-2" placeholder="Пошук епізоду (серія або озвучка)..." style="width: 250px;">
            <a href="{{ route('episodes.create', $anime->id) }}" class="btn btn-sm btn-primary text-nowrap">
                <i class="fas fa-plus"></i> Додати епізод
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped" id="episodesTable">
            <thead>
                <tr>
                    <th>Серія</th>
                    <th>Озвучка</th>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('episodeSearch');
        const tableBody = document.querySelector('#episodesTable tbody');
        const rows = tableBody.querySelectorAll('tr');

        if (searchInput && rows.length > 0 && !rows[0].querySelector('td[colspan]')) {
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        const checkboxes = document.querySelectorAll('.genre-checkbox');
        const selectedText = document.getElementById('selectedGenresText');

        if (checkboxes.length > 0 && selectedText) {
            function updateSelectedGenres() {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.dataset.name);

                if (selected.length === 0) {
                    selectedText.textContent = 'Виберіть жанри...';
                } else if (selected.length <= 3) {
                    selectedText.textContent = selected.join(', ');
                } else {
                    selectedText.textContent = `Вибрано жанрів: ${selected.length}`;
                }
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedGenres));
            updateSelectedGenres(); // Run on load in case of old values
        }
    });
</script>
@endsection
