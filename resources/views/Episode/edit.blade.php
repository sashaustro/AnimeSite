@extends('layouts.admin')

@section('title', 'Редагувати Епізод')
@section('page_title')
    <span title="Редагувати епізод: {{ $episode->anime->title }}" style="cursor: help;">Редагувати епізод: {{ \Illuminate\Support\Str::limit($episode->anime->title, 50) }}</span>
@endsection

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Інформація про епізод</h3>
    </div>

    <form action="{{ route('episodes.update', $episode->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="form-group">
                <label>Номер серії</label>
                <input type="number" class="form-control" name="episode_number" min="1" value="{{ $episode->episode_number }}" required>
            </div>

            <div class="form-group">
                <label>Озвучка</label>
                <input type="text" class="form-control" name="title" value="{{ $episode->title }}" required>
            </div>

            <div class="form-group">
                <label>Поточне відео</label>
                <div>
                    <a href="{{ route('episodes.play', $episode->id) }}" target="_blank" class="btn btn-sm btn-info">Переглянути поточне відео</a>
                </div>
            </div>

            <div class="form-group mt-3">
                <label>Завантажити новий відеофайл локально по частинах (підходить для великих відео)</label>
                <div id="resumable-drop" style="border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer; background: #f9f9f9; border-radius: 5px;">
                    Перетягніть нове відео сюди або натисніть <button type="button" id="resumable-browse" class="btn btn-sm btn-outline-primary">Вибрати файл</button>
                </div>
                <div class="progress mt-2" style="display:none;" id="resumable-progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%;" id="resumable-progress-bar">0%</div>
                </div>
                <small class="form-text text-success" id="resumable-success" style="display:none; font-weight: bold; margin-top: 10px;">Файл успішно завантажено на сервер! Тепер збережіть зміни.</small>
            </div>

            <div class="form-group mt-3">
                <label>АБО вставити новий URL/Шлях відео</label>
                <input type="text" class="form-control" name="video_url" id="video_url" placeholder="https://www.youtube.com/embed/..." value="{{ str_starts_with($episode->video_url, 'http') ? $episode->video_url : '' }}">
                <small class="form-text text-muted">Якщо ви завантажите файл, це поле заповниться автоматично. Залиште пустим, якщо не хочете змінювати поточне відео.</small>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Зберегти зміни</button>
            <a href="{{ route('anime.edit', $episode->anime_id) }}" class="btn btn-secondary">Скасувати</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script>
    var r = new Resumable({
        target: '{{ route('episodes.upload_chunk') }}',
        chunkSize: 1 * 1024 * 1024, // 1MB
        simultaneousUploads: 3,
        testChunks: true,
        query: {
            _token: '{{ csrf_token() }}'
        }
    });

    if(!r.support) {
        alert('Ваш браузер не підтримує завантаження файлів по частинах.');
    } else {
        r.assignBrowse(document.getElementById('resumable-browse'));
        r.assignDrop(document.getElementById('resumable-drop'));

        r.on('fileAdded', function(file, event){
            document.getElementById('resumable-progress').style.display = 'flex';
            document.getElementById('resumable-success').style.display = 'none';
            r.upload();
        });

        r.on('fileProgress', function(file){
            var percent = Math.floor(file.progress() * 100) + '%';
            document.getElementById('resumable-progress-bar').style.width = percent;
            document.getElementById('resumable-progress-bar').innerHTML = percent;
        });

        r.on('fileSuccess', function(file, message){
            document.getElementById('resumable-success').style.display = 'block';
            document.getElementById('resumable-progress-bar').classList.remove('progress-bar-animated');
            document.getElementById('resumable-progress-bar').classList.add('bg-success');
            var response = JSON.parse(message);
            document.getElementById('video_url').value = response.path;
        });

        r.on('fileError', function(file, message){
            alert('Помилка завантаження файлу: ' + message);
        });
    }
</script>
@endpush
@endsection
