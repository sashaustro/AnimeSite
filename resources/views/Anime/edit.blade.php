@extends('adminlte::page')

@section('title', 'Редагувати: ' . $anime->title)

@section('content_header')
    <h1 class="text-white">Редагувати: {{ $anime->title }}</h1>
@stop

@section('content')
    <div class="card card-dark custom-anime-card">
        <div class="card-header border-0">
            <h3 class="card-title text-pink">Налаштування тайтлу</h3>
        </div>
        <form action="{{ route('anime.update', $anime->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body bg-darker">
                
                <div class="form-group">
                    <label class="text-light">Назва аніме</label>
                    <input type="text" name="title" class="form-control bg-dark text-white border-pink" value="{{ $anime->title }}" required>
                </div>

                <div class="form-group">
                    <label class="text-light">Жанри</label>
                    <!-- Використання Select2 -->
                    <select name="genres[]" id="genres" class="form-control select2" multiple required>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" 
                                {{ $anime->genres->contains($genre->id) ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-light">Опис</label>
                    <textarea name="description" rows="5" class="form-control bg-dark text-white border-pink">{{ $anime->description }}</textarea>
                </div>

                <div class="form-group">
                    <label class="text-light">Постер</label><br>
                    @if($anime->image)
                        <img src="{{ asset('storage/' . $anime->image) }}" class="img-thumbnail bg-dark border-pink mb-2" width="150" alt="Поточний постер">
                    @endif
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="image" accept="image/*">
                        <label class="custom-file-label bg-dark text-light border-pink" for="image">Оберіть новий файл...</label>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-darker border-top-0">
                <button type="submit" class="btn btn-pink px-4"><i class="fas fa-save mr-2"></i> Зберегти зміни</button>
                <a href="{{ route('anime.index') }}" class="btn btn-outline-light ml-2">Скасувати</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <!-- Підключення стилів Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Аніме-стилістика для Select2 та форми */
        .bg-darker { background-color: #141419 !important; }
        .custom-anime-card { border: 1px solid #ff3366; box-shadow: 0 0 15px rgba(255,51,102,0.2); }
        .text-pink { color: #ff3366 !important; }
        .border-pink { border-color: #ff3366 !important; }
        .btn-pink { background-color: #ff3366; color: white; border: none; }
        .btn-pink:hover { background-color: #e62e5c; color: white; box-shadow: 0 0 10px #ff3366; }
        
        /* Стилізація Select2 під темну тему */
        .select2-container--default .select2-selection--multiple { background-color: #343a40; border: 1px solid #ff3366; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #ff3366; border: none; color: white; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color: white; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { color: #141419; }
        .select2-dropdown { background-color: #343a40; border: 1px solid #ff3366; color: white; }
        .select2-container--default .select2-results__option[aria-selected=true] { background-color: #ff3366; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #e62e5c; }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({ placeholder: "Виберіть жанри..." });
        });
    </script>
@stop