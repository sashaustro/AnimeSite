@extends('layouts.admin')

@section('title', 'Список Аніме')
@section('page_title', 'Список Аніме')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h3 class="card-title m-0 font-weight-bold text-primary">Всі аніме в базі</h3>
            <div class="card-tools m-0">
                <a href="{{ route('anime.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Додати нове</a>
            </div>
        </div>

        <style>
            .filter-pill {
                border-radius: 20px;
                border: 1px solid #ced4da;
                height: 32px;
                background-color: #fff;
                color: #495057;
                outline: none;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                font-size: 0.875rem;
                padding-left: 1rem;
                padding-right: 1rem;
                appearance: none;
            }
            .filter-pill:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            /* Custom Select UI */
            .custom-select-wrapper { display: inline-block; position: relative; }
            .custom-select-dropdown {
                display: none; position: absolute; top: calc(100% + 5px); left: 0; min-width: 100%; z-index: 1000;
                background: #fff; border-radius: 12px; max-height: 250px; overflow-y: auto;
                border: 1px solid #e9ecef; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 5px 0;
            }
            .custom-select-dropdown.show { display: block; animation: fadeInDown 0.2s ease-out; }
            .custom-select-item {
                padding: 8px 15px; cursor: pointer; font-size: 0.875rem; color: #495057; transition: all 0.2s;
                white-space: nowrap;
            }
            .custom-select-item:hover { background: #f8f9fa; color: #17a2b8; padding-left: 20px; }
            .custom-select-item.active { background: #f1f3f5; font-weight: 600; color: #212529; border-left: 3px solid #17a2b8;}

            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-5px); }
                to { opacity: 1; transform: translateY(0); }
            }

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
                width: 300px; opacity: 1; padding: 0 30px 0 35px; border: 1px solid #ced4da;
            }
            .search-expandable .btn-search-icon {
                background: #fff; border: 1px solid #ced4da; height: 32px; width: 35px;
                color: #6c757d; border-radius: 20px; display: flex; align-items: center; justify-content: center;
                cursor: pointer; z-index: 2; transition: all 0.3s;
            }
            .search-expandable .btn-search-icon:hover {
                color: #17a2b8;
                border-color: #17a2b8;
            }
            .search-expandable.active .btn-search-icon {
                position: absolute; left: 0; top: 0; height: 100%; border: none; background: transparent;
                color: #495057;
            }
            .search-expandable.active .btn-search-icon:hover {
                color: #17a2b8;
            }
            .search-expandable .close-search {
                display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                color: #adb5bd; cursor: pointer; z-index: 3;
            }
            .search-expandable.active .close-search { display: block; }
            .search-expandable.active .close-search:hover { color: #dc3545; }
        </style>

        <div class="card-body bg-light border-bottom py-2">
            <form action="{{ route('anime.admin_index') }}" method="GET" class="d-flex flex-wrap align-items-center" id="animeFilterForm" style="gap: 10px;">

                <div class="search-expandable {{ request('search') ? 'active' : '' }}" id="animeSearchContainer">
                    <button type="button" class="btn-search-icon" id="toggleAnimeSearch" title="Пошук">
                        <i class="fas fa-search"></i>
                    </button>
                    <input type="text" name="search" id="animeSearchInput" placeholder="Пошук за назвою..." value="{{ request('search') }}">
                    <i class="fas fa-times close-search" id="closeAnimeSearch" title="Очистити"></i>
                </div>

                <select name="status" class="filter-pill" style="width: 130px;" onchange="this.form.submit()">
                    <option value="">Всі статуси</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Онгоїнг</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                    <option value="announced" {{ request('status') == 'announced' ? 'selected' : '' }}>Анонс</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Призупинено</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Скасовано</option>
                </select>
                <select name="genre" class="filter-pill" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Всі жанри</option>
                    @foreach($genres as $g)
                        <option value="{{ $g->id }}" {{ request('genre') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
                <select name="year" class="filter-pill" style="width: 110px;" onchange="this.form.submit()">
                    <option value="">Всі роки</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select name="country" class="filter-pill" style="width: 130px;" onchange="this.form.submit()">
                    <option value="">Всі країни</option>
                    @foreach($countries as $c)
                        <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <select name="format" class="filter-pill" style="width: 130px;" onchange="this.form.submit()">
                    <option value="">Всі формати</option>
                    @foreach($formats as $f)
                        <option value="{{ $f }}" {{ request('format') == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['search', 'status', 'genre', 'year', 'country', 'format']))
                    <a href="{{ route('anime.admin_index') }}" class="btn btn-sm rounded-pill px-3" style="background: #e9ecef; color: #495057; height: 32px; display: flex; align-items: center; border: 1px solid #ced4da;" title="Скинути фільтри">
                        <i class="fas fa-times mr-1"></i> Скинути
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">№</th>
                        <th class="border-0">Постер</th>
                        <th class="border-0" style="min-width: 150px;">Назва</th>
                        <th class="border-0">Рік/Статус</th>
                        <th class="border-0">Країна/Студія/Формат</th>
                        <th class="border-0">Жанри</th>
                        <th class="border-0" style="max-width: 200px;">Опис</th>
                        <th class="border-0 text-right">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animes as $anime)
                        <tr ondblclick="window.location='{{ route('anime.edit', $anime->id) }}'" style="cursor: pointer;" title="Двічі клацніть, щоб редагувати">
                            <td><strong>{{ ($animes->currentPage() - 1) * $animes->perPage() + $loop->iteration }}</strong></td>
                            <td>
                                @if($anime->image)
                                    <img src="{{ str_starts_with($anime->image, 'http') ? $anime->image : asset('storage/' . $anime->image) }}" alt="poster" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="badge badge-secondary">Немає</span>
                                @endif
                            </td>
                            <td style="max-width: 250px;">
                                <a href="{{ route('anime.show', $anime->id) }}" target="_blank" class="text-dark font-weight-bold d-block text-truncate" onclick="event.stopPropagation()" title="{{ $anime->title }}">
                                    {{ $anime->title }}
                                </a>
                            </td>
                            <td>
                                <span class="d-block">{{ $anime->year ?? '—' }}</span>
                                <span class="badge badge-{{ $anime->status == 'ongoing' ? 'success' : ($anime->status == 'completed' ? 'primary' : 'warning') }}">{{ $anime->status }}</span>
                            </td>
                            <td style="font-size: 0.85rem;">
                                <div><strong>Країна:</strong> {{ $anime->country ?: '—' }}</div>
                                <div><strong>Студія:</strong> {{ $anime->studio ?: '—' }}</div>
                                <div><strong>Формат:</strong> {{ $anime->format ?: '—' }}</div>
                            </td>
                            <td>
                                @if($anime->genres && $anime->genres->count() > 0)
                                    @foreach($anime->genres as $genre)
                                        <span class="badge badge-info">{{ $genre->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.85rem;" title="{{ $anime->description }}">
                                {{ $anime->description ?: '—' }}
                            </td>
                        <td class="text-right" onclick="event.stopPropagation()">
                            <a href="{{ route('anime.edit', $anime->id) }}" class="btn btn-sm btn-info" title="Редагувати"><i class="fas fa-edit"></i></a>

                            <button type="button" class="btn btn-sm btn-danger" title="Видалити" onclick="openConfirmModal('{{ route('anime.destroy', $anime->id) }}', 'Ви впевнені, що хочете видалити це аніме?', 'Видалити', 'btn-danger', 'DELETE')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Немає жодного аніме в базі</td>
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
        const toggleBtn = document.getElementById('toggleAnimeSearch');
        const searchContainer = document.getElementById('animeSearchContainer');
        const searchInput = document.getElementById('animeSearchInput');
        const closeBtn = document.getElementById('closeAnimeSearch');

        if (toggleBtn && searchContainer && searchInput && closeBtn) {
            toggleBtn.addEventListener('click', function(e) {
                if (!searchContainer.classList.contains('active')) {
                    e.preventDefault();
                    searchContainer.classList.add('active');
                    searchInput.focus();
                } else if (searchInput.value.trim() !== '') {
                    document.getElementById('animeFilterForm').submit();
                } else {
                    e.preventDefault();
                    searchContainer.classList.remove('active');
                }
            });

            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                if (window.location.search.includes('search=')) {
                    document.getElementById('animeFilterForm').submit();
                } else {
                    searchContainer.classList.remove('active');
                }
            });
        }

        // Custom Select Implementation
        document.querySelectorAll('select.filter-pill').forEach(function(select) {
            select.style.display = 'none';

            let wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';
            wrapper.style.width = select.style.width || '130px';

            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);

            let selectedOption = select.options[select.selectedIndex] || select.options[0];

            let trigger = document.createElement('div');
            trigger.className = 'filter-pill d-flex justify-content-between align-items-center';
            trigger.style.cursor = 'pointer';
            trigger.style.userSelect = 'none';
            trigger.innerHTML = `<span class="text-truncate" style="max-width: 85%;">${selectedOption.text}</span> <i class="fas fa-chevron-down" style="font-size:0.7em; color:#adb5bd;"></i>`;

            wrapper.appendChild(trigger);

            let dropdown = document.createElement('div');
            dropdown.className = 'custom-select-dropdown shadow-sm';

            Array.from(select.options).forEach(function(option) {
                let item = document.createElement('div');
                item.className = 'custom-select-item' + (option.selected ? ' active' : '');
                item.innerText = option.text;
                item.dataset.value = option.value;
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    select.value = this.dataset.value;
                    trigger.querySelector('span').innerText = this.innerText;
                    dropdown.classList.remove('show');
                    select.dispatchEvent(new Event('change'));
                });
                dropdown.appendChild(item);
            });

            wrapper.appendChild(dropdown);

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                let isShowing = dropdown.classList.contains('show');
                document.querySelectorAll('.custom-select-dropdown.show').forEach(d => d.classList.remove('show'));
                if (!isShowing) dropdown.classList.add('show');
            });
        });

        document.addEventListener('click', function() {
            document.querySelectorAll('.custom-select-dropdown.show').forEach(d => d.classList.remove('show'));
        });
    });
</script>
@endpush
