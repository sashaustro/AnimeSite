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

            <select name="role" class="filter-pill" style="width: 180px;" onchange="this.form.submit()">
                <option value="">Всі ролі</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Користувач</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Адміністратор</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Головний адміністратор</option>
            </select>

            <select name="sort" class="filter-pill" style="width: 160px;" onchange="this.form.submit()">
                <option value="oldest" {{ request('sort', 'oldest') == 'oldest' ? 'selected' : '' }}>Спочатку старі (1, 2...)</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Спочатку нові (9, 8...)</option>
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
            ['title' => 'Головні Адміністратори', 'users' => $users->where('role', 'super_admin'), 'color' => 'danger'],
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
                                    @if($user->role === 'super_admin')
                                        <span class="badge badge-danger px-2 py-1" style="background-color: #8b0000;">Головний адміністратор</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge badge-danger px-2 py-1">Адміністратор</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Користувач</span>
                                    @endif
                                    @if($user->scheduled_for_deletion_at)
                                        <br><span class="badge badge-warning px-2 py-1 mt-1 text-dark" style="font-size: 0.75rem;">Видаляється (залиш. {{ round(now()->diffInDays($user->scheduled_for_deletion_at)) }} дн)</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="d-flex justify-content-end align-items-center" style="gap: 5px;">
                                        <!-- Send Password Reset -->
                                        <button type="button" class="btn btn-sm btn-info text-white" title="Скинути пароль" onclick="openConfirmModal('{{ route('admin.users.send_reset_link', $user->id) }}', 'Надіслати лист для скидання пароля цьому користувачу?', 'Надіслати', 'btn-info')">
                                            <i class="fas fa-key"></i>
                                        </button>

                                        @if(auth()->id() !== $user->id && $user->role !== 'super_admin')
                                            <!-- Change Role -->
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openRoleModal('{{ route('admin.users.update_role', $user->id) }}', '{{ $user->role }}')" title="Змінити роль">
                                                <i class="fas fa-user-cog"></i>
                                            </button>

                                            <!-- Delete / Restore -->
                                            @if($user->scheduled_for_deletion_at)
                                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Скасувати видалення">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" title="Миттєве видалення" onclick="openConfirmModal('{{ route('admin.users.force_delete', $user->id) }}', 'Ви впевнені? Це остаточно видалить акаунт без можливості відновлення!', 'Миттєво видалити', 'btn-danger', 'DELETE')">
                                                    <i class="fas fa-skull-crossbones"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-danger" title="Видалити користувача" onclick="openConfirmModal('{{ route('admin.users.destroy', $user->id) }}', 'Ви впевнені, що хочете видалити цього користувача? У нього буде 7 днів на відновлення.', 'Видалити', 'btn-danger', 'DELETE')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
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

<!-- Role Password Modal -->
<div class="modal fade" id="rolePasswordModal" tabindex="-1" role="dialog" aria-labelledby="rolePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="roleToggleForm" method="POST" action="">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rolePasswordModalLabel">Підтвердження зміни ролі</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="new_role" class="font-weight-bold">Оберіть нову роль</label>
            <select name="new_role" id="new_role" class="form-control" required>
                <option value="user">Користувач</option>
                <option value="admin">Адміністратор</option>
            </select>
            <small class="text-muted d-block mt-1">Головного адміністратора (super_admin) можна призначити лише через базу даних.</small>
          </div>
          <p class="text-muted mb-3" style="font-size: 0.9rem;">Введіть ваш пароль адміністратора для підтвердження дії. Це зроблено заради безпеки.</p>
          <div class="form-group">
            <label for="admin_password" class="font-weight-bold">Ваш пароль</label>
            <input type="password" name="admin_password" id="admin_password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Скасувати</button>
          <button type="submit" class="btn btn-primary" id="roleSubmitBtn">Зберегти роль</button>
        </div>
      </div>
    </form>
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

<script>
function openRoleModal(actionUrl, currentRole) {
    document.getElementById('roleToggleForm').action = actionUrl;
    
    let roleSelect = document.getElementById('new_role');
    roleSelect.innerHTML = ''; // Clear existing options
    
    if (currentRole === 'admin') {
        roleSelect.innerHTML = '<option value="user">Користувач</option>';
    } else {
        roleSelect.innerHTML = '<option value="admin">Адміністратор</option>';
    }
    
    document.getElementById('admin_password').value = '';
    $('#rolePasswordModal').modal('show');
}

function openConfirmModal(actionUrl, message, btnText, btnClass, method = 'POST') {
    document.getElementById('actionConfirmForm').action = actionUrl;
    document.getElementById('actionConfirmMessage').innerText = message;
    
    let btn = document.getElementById('actionSubmitBtn');
    btn.innerText = btnText;
    btn.className = 'btn ' + btnClass;
    
    document.getElementById('actionMethod').value = method;
    
    $('#actionConfirmModal').modal('show');
}
</script>
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
