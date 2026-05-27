@extends('layouts.admin')

@section('title', 'Список Аніме')
@section('page_title', 'Список Аніме')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Інтерактивна таблиця аніме</h3>
    </div>
    
    <div class="card-body">
        <table id="animeTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Постер</th>
                    <th>Назва</th>
                    <th>Жанри</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($animes as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" width="50" style="border-radius: 5px;" alt="Постер">
                            @else
                                Немає
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>
                            @if(isset($item->genres))
                                @foreach($item->genres as $genre)
                                    <span class="badge badge-info">{{ $genre->name }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('anime.edit', $item->id) }}" class="btn btn-sm btn-warning">Редагувати</a>
                            
                            <form action="{{ route('anime.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ви впевнені?')">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#animeTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/uk.json"
                }
            });
        });
    </script>
@endpush