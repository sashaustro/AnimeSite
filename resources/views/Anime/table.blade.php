<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблиця Аніме (DataTables)</title>
    <!-- CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            background-color: #1a1a1d;
            color: white;
            font-family: sans-serif;
            padding: 20px;
        }
        /* Адаптація DataTables для темної теми */
        table.dataTable { background-color: #252529; color: white; border-radius: 8px; overflow: hidden; }
        table.dataTable tbody tr { background-color: #252529; }
        table.dataTable tbody tr:hover { background-color: #333; }
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate {
            color: white !important;
            margin-bottom: 15px;
            margin-top: 15px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: white !important; }
    </style>
</head>
<body>
    <div style="margin-bottom: 20px;">
        <a href="{{ route('anime.index') }}" style="color: #ff3366; text-decoration: none; font-size: 16px;">&larr; Повернутися до каталогу</a>
    </div>

    <h2>Інтерактивна таблиця аніме</h2>

    <table id="animeTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Постер</th>
                <th>Назва</th>
                <th>Жанр</th>
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
                    <td>{{ $item->genre }}</td>
                    <td>
                        <a href="{{ route('anime.show', $item->id) }}" style="color: #4CAF50; text-decoration: none;">Переглянути</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Скрипти -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#animeTable').DataTable({
                // Підключення української мови для таблиці
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/uk.json"
                }
            });
        });
    </script>
</body>
</html>