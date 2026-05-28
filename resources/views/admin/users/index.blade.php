@extends('layouts.admin')

@section('title', 'Користувачі')
@section('page_title', 'Керування користувачами')

@section('content')
<div class="card shadow-sm border-0 mb-4">
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
            cursor: pointer; z-index: 2; transition: color 0.3s;
        }
        .search-expandable.active .btn-search-icon {
            position: absolute; left: 0; top: 0; height: 100%; border: none; background: transparent;
            color: #495057;
        }
        .search-expandable .close-search {
            display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            color: #adb5bd; cursor: pointer; z-index: 3;
        }
        .search-expandable.active .close-search { display: block; }
        .search-expandable.active .close-search:hover { color: #dc3545; }
    </style>

    <div class="card-header bg-white py-3">
        <h3 class="card-title m-0 font-weight-bold text-primary">Список всіх користувачів</h3>
    </div>

    <div class="card-body bg-light border-bottom py-2">
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex flex-wrap align-items-center" id="userFilterForm" style="gap: 10px;">
            <div class="search-expandable {{ request('search') ? 'active' : '' }}" id="userSearchContainer">
                <button type="button" class="btn-search-icon" id="toggleUserSearch" title="Пошук">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" name="search" id="userSearchInput" placeholder="Пошук за ID, ім'ям, нікнеймом або email..." value="{{ request('search') }}">
                <i class="fas fa-times close-search" id="closeUserSearch" title="Очистити"></i>
            </div>

            <select name="role" class="filter-pill" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Всі ролі</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Користувач</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Адміністратор</option>
            </select>

            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm rounded-pill px-3" style="background: #e9ecef; color: #495057; height: 32px; display: flex; align-items: center; border: 1px solid #ced4da;" title="Скинути фільтри">
                    <i class="fas fa-times mr-1"></i> Скинути
                </a>
            @endif
        </form>
    </div>

    @php
        $groups = [
            ['title' => 'Адміністратори', 'users' => $users->where('role', 'admin'), 'color' => 'danger'],
            ['title' => 'Користувачі', 'users' => $users->where('role', 'user'), 'color' => 'secondary']
        ];
    @endphp

    @foreach($groups as $index => $group)
        @if($group['users']->count() > 0)
            <div class="card-header bg-light {{ $index > 0 ? 'border-top' : '' }} border-bottom-0 py-2">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="m-0 font-weight-bold text-{{ $group['color'] }} text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $group['title'] }}</h6>
                    <span class="badge badge-{{ $group['color'] }} px-2 py-1">{{ $group['users']->count() }} на цій сторінці</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-white">
                        <tr>
                            <th class="border-0 border-bottom text-muted" style="font-size: 0.85rem; width: 5%;">ID</th>
                            <th class="border-0 border-bottom text-muted" style="font-size: 0.85rem; width: 20%;">Ім'я</th>
                            <th class="border-0 border-bottom text-muted" style="font-size: 0.85rem; width: 15%;">Нікнейм</th>
                            <th class="border-0 border-bottom text-muted" style="font-size: 0.85rem; width: 25%;">Email</th>
                            <th class="border-0 border-bottom text-muted" style="font-size: 0.85rem; width: 15%;">Роль</th>
                            <th class="border-0 border-bottom text-right text-muted" style="font-size: 0.85rem; width: 20%;">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['users'] as $user)
                            <tr>
                                <td><strong>{{ $user->id }}</strong></td>
                                <td>{{ $user->name }}</td>
                                <td><a href="{{ route('profile.public', $user->id) }}" target="_blank" class="text-dark font-weight-bold">{{ $user->username ?? '—' }}</a></td>
                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-danger px-2 py-1">Адміністратор</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Користувач</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.toggle_role', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->role === 'admin' ? 'btn-warning' : 'btn-success' }}">
                                                @if($user->role === 'admin')
                                                    Забрати права адміна
                                                @else
                                                    Зробити адміном
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Це ви</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

    @if($users->isEmpty())
        <div class="card-body text-center text-muted py-4">
            Користувачів не знайдено
        </div>
    @endif

    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleUserSearch');
        const searchContainer = document.getElementById('userSearchContainer');
        const searchInput = document.getElementById('userSearchInput');
        const closeBtn = document.getElementById('closeUserSearch');

        if (toggleBtn && searchContainer && searchInput && closeBtn) {
            toggleBtn.addEventListener('click', function(e) {
                if (!searchContainer.classList.contains('active')) {
                    e.preventDefault();
                    searchContainer.classList.add('active');
                    searchInput.focus();
                } else if (searchInput.value.trim() !== '') {
                    document.getElementById('userFilterForm').submit();
                } else {
                    e.preventDefault();
                    searchContainer.classList.remove('active');
                }
            });

            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                if (window.location.search.includes('search=')) {
                    document.getElementById('userFilterForm').submit();
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
