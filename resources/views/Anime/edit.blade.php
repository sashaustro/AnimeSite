@extends('layouts.admin')

@section('title', 'Редагувати Аніме')
@section('page_title')
    <span title="{{ $anime->title }}" style="cursor: help;">Редагувати: {{ \Illuminate\Support\Str::limit($anime->title, 50) }}</span>
@endsection

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
                            <label>Оригінальна назва (Romaji / English / Japanese)</label>
                            <input type="text" class="form-control" name="original_title" value="{{ $anime->original_title }}" placeholder="Наприклад: Yuusha no Kuzu (необов'язково)">
                        </div>

                        <div class="form-group">
                            <label>Опис</label>
                            <textarea name="description" class="form-control" rows="5" style="min-height: 120px; resize: vertical;">{{ $anime->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Автор</label>
                            <input type="text" class="form-control" name="author" placeholder="Введіть автора (необов'язково)" value="{{ $anime->author }}">
                        </div>
                        <div class="form-group">
                            <label>Першоджерело</label>
                            <input type="text" list="sources" class="form-control" name="source" value="{{ ($anime->source === '—' || empty($anime->source)) ? '' : $anime->source }}" placeholder="—">
                            <datalist id="sources">
                                <option value="Манга">
                                <option value="Ранобе">
                                <option value="Оригінал">
                                <option value="Візуальна новела">
                                <option value="Гра">
                                <option value="Манхва">
                            </datalist>
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

                                <optgroup label="Поточний рік">
                                    <option value="{{ $currentYear }}" {{ $anime->year == $currentYear ? 'selected' : '' }}>{{ $currentYear }}</option>
                                </optgroup>

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
                            <label>Пора року (Сезон)</label>
                            <select name="season" class="form-control">
                                <option value="">— (Не вказано)</option>
                                <option value="Зима" {{ $anime->season == 'Зима' ? 'selected' : '' }}>Зима</option>
                                <option value="Весна" {{ $anime->season == 'Весна' ? 'selected' : '' }}>Весна</option>
                                <option value="Літо" {{ $anime->season == 'Літо' ? 'selected' : '' }}>Літо</option>
                                <option value="Осінь" {{ $anime->season == 'Осінь' ? 'selected' : '' }}>Осінь</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Загальна кількість епізодів</label>
                                    <input type="number" class="form-control" name="total_episodes" placeholder="Наприклад: 12, 24..." min="1" value="{{ $anime->total_episodes }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Тривалість (хвилин)</label>
                                    <input type="text" class="form-control" name="duration" placeholder="Наприклад: 24 хв" value="{{ $anime->duration }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>День виходу (для онгоїнгів)</label>
                                    <select name="broadcast_day" class="form-control">
                                        <option value="">— (Не вказано)</option>
                                        <option value="Понеділок" {{ $anime->broadcast_day == 'Понеділок' ? 'selected' : '' }}>Понеділок</option>
                                        <option value="Вівторок" {{ $anime->broadcast_day == 'Вівторок' ? 'selected' : '' }}>Вівторок</option>
                                        <option value="Середа" {{ $anime->broadcast_day == 'Середа' ? 'selected' : '' }}>Середа</option>
                                        <option value="Четвер" {{ $anime->broadcast_day == 'Четвер' ? 'selected' : '' }}>Четвер</option>
                                        <option value="П'ятниця" {{ $anime->broadcast_day == "П'ятниця" ? 'selected' : '' }}>П'ятниця</option>
                                        <option value="Субота" {{ $anime->broadcast_day == 'Субота' ? 'selected' : '' }}>Субота</option>
                                        <option value="Неділя" {{ $anime->broadcast_day == 'Неділя' ? 'selected' : '' }}>Неділя</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Час виходу</label>
                                    <input type="time" name="broadcast_time" class="form-control" value="{{ $anime->broadcast_time ? \Carbon\Carbon::parse($anime->broadcast_time)->format('H:i') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Формат</label>
                            <input type="text" list="formats" class="form-control" name="format" value="{{ ($anime->format === 'Невідомо' || $anime->format === '—') ? '' : $anime->format }}" placeholder="—">
                            <datalist id="formats">
                                <option value="TV Серіал">
                                <option value="Фільм">
                                <option value="OVA">
                                <option value="ONA">
                                <option value="Спешл">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Країна</label>
                            <input type="text" list="countries" class="form-control" name="country" value="{{ ($anime->country === 'Невідомо' || $anime->country === '—') ? '' : $anime->country }}" placeholder="—">
                            <datalist id="countries">
                                <option value="Японія">
                                <option value="Південна Корея">
                                <option value="Китай">
                                <option value="США">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Студія</label>
                            <input type="text" list="studios" class="form-control" name="studio" value="{{ ($anime->studio === 'Невідомо' || $anime->studio === '—') ? '' : $anime->studio }}" placeholder="—">
                            <datalist id="studios">
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
                                    <input type="text" id="genreSearch" class="form-control mb-3" placeholder="Пошук жанру..." onclick="event.stopPropagation()">
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title m-0">Епізоди (Відео)</h3>
        <div class="card-tools d-flex align-items-center m-0 ml-auto">
            <style>
                .search-expandable { position: relative; display: flex; align-items: center; }
                .search-expandable .btn-search-icon { position: absolute; left: 0; top: 0; background: transparent; border: none; height: 31px; width: 35px; color: #495057; font-size: 1.1rem; border-radius: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; }
                .search-expandable input { width: 35px; opacity: 0; padding: 0; border: 1px solid transparent; transition: width 0.3s ease, opacity 0.3s ease, padding 0.3s ease, background 0.3s ease; background: transparent; border-radius: 20px; height: 31px; outline: none; cursor: pointer; }
                .search-expandable.active input { width: 250px; opacity: 1; padding: 0 15px 0 35px; border: 1px solid #ced4da; background: #fff; margin-right: 10px; cursor: text; }
                .search-expandable.active .btn-search-icon:hover { color: #17a2b8; }
                .search-expandable .close-search { display: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd; cursor: pointer; z-index: 3; }
                .search-expandable.active .close-search { display: block; }
                .search-expandable.active .close-search:hover { color: #dc3545; }
            </style>
            <div class="search-expandable mr-3" id="episodeSearchForm">
                <button type="button" class="btn-search-icon" id="toggleEpisodeSearch" title="Пошук">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" id="episodeSearch" placeholder="Пошук епізоду (серія або озвучка)...">
                <i class="fas fa-times close-search" id="closeEpisodeSearch" title="Очистити"></i>
            </div>
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
            // Search input logic
            searchInput.addEventListener('input', function() {
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

            // Expandable search logic
            const searchForm = document.getElementById('episodeSearchForm');
            const toggleBtn = document.getElementById('toggleEpisodeSearch');
            const closeBtn = document.getElementById('closeEpisodeSearch');

            toggleBtn.addEventListener('click', function(e) {
                if (!searchForm.classList.contains('active')) {
                    searchForm.classList.add('active');
                    searchInput.focus();
                } else if (searchInput.value.trim() === '') {
                    searchForm.classList.remove('active');
                }
            });

            closeBtn.addEventListener('click', function(e) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input')); // trigger filtering
                searchForm.classList.remove('active');
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

            const genreSearch = document.getElementById('genreSearch');
            if (genreSearch) {
                genreSearch.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    checkboxes.forEach(cb => {
                        const label = cb.dataset.name.toLowerCase();
                        const container = cb.closest('.col-md-4');
                        if (label.includes(filter)) {
                            container.style.display = '';
                        } else {
                            container.style.display = 'none';
                        }
                    });
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedGenres));
            updateSelectedGenres(); // Run on load in case of old values
        }
    });
</script>
@endsection
