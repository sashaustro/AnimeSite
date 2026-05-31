@extends('layouts.admin')

@section('title', 'Жанри')
@section('page_title', 'Керування жанрами')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <style>
        .search-expandable {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-expandable input {
            width: 0; opacity: 0; padding: 0; border: none;
            transition: width 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
            background: #fff; border-radius: 20px; height: 32px; outline: none; font-size: 0.875rem;
        }
        .search-expandable.active input {
            width: 300px; opacity: 1; padding: 0 30px 0 35px; border: 1px solid #ced4da; margin-right: 10px;
        }
        .search-expandable .btn-search-icon {
            background: #fff; border: 1px solid #ced4da; height: 32px; width: 35px;
            color: #6c757d; border-radius: 20px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 2; transition: color 0.3s;
        }
        .search-expandable.active .btn-search-icon {
            position: absolute; left: 0; top: 0; height: 100%; border: none; background: transparent;
            color: #495057;
        }
        .search-expandable.active .btn-search-icon:hover {
            color: #17a2b8;
        }
        .search-expandable .close-search {
            display: none; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
            color: #adb5bd; cursor: pointer; z-index: 3;
        }
        .search-expandable.active .close-search { display: block; }
        .search-expandable.active .close-search:hover { color: #dc3545; }
    </style>

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title m-0 font-weight-bold text-primary">Список всіх жанрів</h3>
        <div class="card-tools d-flex align-items-center m-0 ml-auto">
            <form action="{{ route('admin.genres.index') }}" method="GET" class="search-expandable mr-3 {{ request('search') ? 'active' : '' }}" id="genreSearchForm">
                <button type="button" class="btn-search-icon" id="toggleGenreSearch" title="Пошук">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" name="search" id="genreSearchInput" placeholder="Пошук жанру..." value="{{ request('search') }}">
                <i class="fas fa-times close-search" id="closeGenreSearch" title="Очистити"></i>
            </form>
            <a href="{{ route('admin.genres.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Додати жанр</a>
        </div>
    </div>
    
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0">№</th>
                    <th class="border-0">Назва</th>
                    <th class="border-0 text-right">Дії</th>
                </tr>
            </thead>
            <tbody>
                @forelse($genres as $genre)
                    <tr>
                        <td><strong>{{ $loop->iteration }}</strong></td>
                        <td>{{ $genre->name }}</td>
                        <td class="text-right">
                            <div style="display: inline-block;">
                                <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-sm btn-info" title="Редагувати"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="openConfirmModal('{{ route('admin.genres.destroy', $genre->id) }}', 'Ви впевнені, що хочете видалити цей жанр?', 'Видалити', 'btn-danger', 'DELETE')" title="Видалити"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Жодного жанру ще не створено.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Action Confirm Modal -->
<div class="modal fade" id="actionConfirmModal" tabindex="-1" role="dialog" aria-labelledby="actionConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="actionConfirmForm" method="POST" action="">
      @csrf
      <input type="hidden" name="_method" id="actionMethod" value="POST">
      
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="actionConfirmModalLabel">Підтвердження дії</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="actionConfirmMessage" style="font-size: 1rem; margin-bottom: 0;">Ви впевнені?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Скасувати</button>
          <button type="submit" class="btn btn-primary" id="actionSubmitBtn">Підтвердити</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
    function openConfirmModal(actionUrl, message, btnText, btnClass, method = 'POST') {
        document.getElementById('actionConfirmForm').action = actionUrl;
        document.getElementById('actionConfirmMessage').innerText = message;
        
        let btn = document.getElementById('actionSubmitBtn');
        btn.innerText = btnText;
        btn.className = 'btn ' + btnClass;
        
        document.getElementById('actionMethod').value = method;
        
        $('#actionConfirmModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleGenreSearch');
        const searchForm = document.getElementById('genreSearchForm');
        const searchInput = document.getElementById('genreSearchInput');
        const closeBtn = document.getElementById('closeGenreSearch');

        toggleBtn.addEventListener('click', function(e) {
            if (!searchForm.classList.contains('active')) {
                e.preventDefault();
                searchForm.classList.add('active');
                searchInput.focus();
            } else if (searchInput.value.trim() !== '') {
                searchForm.submit();
            } else {
                e.preventDefault();
                searchForm.classList.remove('active');
            }
        });

        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            if (window.location.search.includes('search=')) {
                window.location.href = "{{ route('admin.genres.index') }}";
            } else {
                searchForm.classList.remove('active');
            }
        });
    });
</script>
@endpush
