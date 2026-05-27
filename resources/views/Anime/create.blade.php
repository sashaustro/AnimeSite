@extends('layouts.admin')

@section('title', 'Додати Аніме')
@section('page_title', 'Додати нове аніме в каталог')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Основна інформація</h3>
    </div>

    <form action="{{ route('anime.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Назва аніме</label>
                        <input type="text" class="form-control" name="title" required placeholder="Введіть назву">
                    </div>
                    <div class="form-group">
                        <label>Опис</label>
                        <textarea name="description" class="form-control" rows="5" style="min-height: 120px; resize: vertical;" placeholder="Введіть опис"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Постер (Зображення)</label>
                        <div class="input-group mb-2">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept="image/*">
                                <label class="custom-file-label">Оберіть файл</label>
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

                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>

                            <optgroup label="Анонси (Майбутні роки)">
                                @for($y = $currentYear + 1; $y <= $currentYear + 5; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </optgroup>

                            <optgroup label="Минулі роки">
                                @for($y = $currentYear - 1; $y >= 1950; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Формат</label>
                        <input type="text" list="formats" class="form-control" name="format" placeholder="Серіал, Фільм, OVA" value="Невідомо">
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
                        <input type="text" list="countries" class="form-control" name="country" placeholder="Японія, Китай, Корея" value="Невідомо">
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
                        <input type="text" list="studios" class="form-control" name="studio" placeholder="Mappa, Ufotable, etc." value="Невідомо">
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
                            <option value="ongoing">Онгоїнг (Ongoing)</option>
                            <option value="completed">Завершено (Completed)</option>
                            <option value="announced">Анонс (Announced)</option>
                            <option value="paused">Призупинено (Paused)</option>
                            <option value="cancelled">Скасовано (Cancelled)</option>
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
                                    @foreach($genres as $genre)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input genre-checkbox" type="checkbox" name="genres[]" id="genre_{{ $genre->id }}" value="{{ $genre->id }}" data-name="{{ $genre->name }}">
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
            <button type="submit" class="btn btn-primary">Зберегти Аніме</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.genre-checkbox');
    const selectedText = document.getElementById('selectedGenresText');

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
});
</script>
@endsection
