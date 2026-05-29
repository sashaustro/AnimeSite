@extends('layouts.admin')

@section('title', 'Скарги - Адмін-панель')

@section('content')
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
        width: 300px; opacity: 1; padding: 0 30px 0 35px; border: 1px solid #ced4da; margin-right: 10px;
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
        display: none; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
        color: #adb5bd; cursor: pointer; z-index: 3;
    }
    .search-expandable.active .close-search { display: block; }
    .search-expandable.active .close-search:hover { color: #dc3545; }
</style>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="card-title m-0 font-weight-bold text-primary">Скарги</h3>
    </div>
    
    <div class="card-body bg-light border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <!-- Вкладки -->
            <ul class="nav nav-pills m-0">
                <li class="nav-item mr-2">
                    <a class="nav-link {{ $tab == 'comment' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'comment']) }}" style="{{ $tab == 'comment' ? '' : 'color: #495057; background: #fff; border: 1px solid #ced4da;' }}">Коментарі</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link {{ $tab == 'player' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'player']) }}" style="{{ $tab == 'player' ? '' : 'color: #495057; background: #fff; border: 1px solid #ced4da;' }}">Плеєр</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'info' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'info']) }}" style="{{ $tab == 'info' ? '' : 'color: #495057; background: #fff; border: 1px solid #ced4da;' }}">Інфо (Опис)</a>
                </li>
            </ul>

            <!-- Фільтр і пошук -->
            <form action="{{ route('admin.reports.index') }}" method="GET" class="d-flex align-items-center" id="reportFilterForm" style="gap: 10px; margin: 0;">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="search-expandable {{ request('search') ? 'active' : '' }}" id="reportSearchContainer">
                    <button type="button" class="btn-search-icon" id="toggleReportSearch" title="Пошук">
                        <i class="fas fa-search"></i>
                    </button>
                    <input type="text" name="search" id="reportSearchInput" placeholder="Пошук..." value="{{ request('search') }}">
                    <i class="fas fa-times close-search" id="closeReportSearch" title="Очистити"></i>
                </div>
                
                <select name="status" class="filter-pill" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Всі статуси</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Очікує</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Вирішено</option>
                </select>
                
                @if(request('status'))
                    <a href="{{ route('admin.reports.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary btn-sm filter-pill d-flex align-items-center justify-content-center" style="width: 32px; padding: 0;" title="Скинути фільтри"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div id="toast-success" style="position: fixed; bottom: 20px; right: 20px; background: #343a40; color: #fff; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; display: flex; align-items: center; gap: 10px; opacity: 1; transition: opacity 0.5s;">
            <i class="fas fa-check-circle" style="color: #28a745;"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                let toast = document.getElementById('toast-success');
                if(toast) {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0" style="width: 5%">#</th>
                        <th class="border-0" style="width: 15%">Від кого / На кого</th>
                        <th class="border-0" style="width: 20%">Об'єкт</th>
                        <th class="border-0" style="width: 30%">Причина / Повідомлення</th>
                        <th class="border-0" style="width: 10%">Статус</th>
                        <th class="border-0" style="width: 20%">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>Від:</strong> {{ $report->user->username ?? 'Видалений' }}<br>
                                @if($report->type == 'comment')
                                    @php $comment = \App\Models\Comment::find($report->reference_id); @endphp
                                    @if($comment && $comment->user)
                                        <small class="text-muted"><strong>На:</strong> {{ $comment->user->username }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($report->type == 'comment')
                                    @php $comment = \App\Models\Comment::find($report->reference_id); @endphp
                                    @if($comment)
                                        <a href="{{ route('anime.show', $comment->anime_id) }}#comment-{{ $comment->id }}" target="_blank">Коментар #{{ $comment->id }}</a>
                                    @else
                                        <span class="text-danger">Коментар видалено</span>
                                    @endif
                                @else
                                    <a href="{{ route('anime.show', $report->reference_id) }}" target="_blank">Аніме #{{ $report->reference_id }}</a>
                                @endif
                            </td>
                            <td style="max-width: 300px; word-wrap: break-word;">
                                {{ $report->message ?: 'Без тексту' }}
                            </td>
                            <td>
                                @if($report->status == 'pending')
                                    <span class="badge badge-warning">Очікує</span>
                                @else
                                    <span class="badge badge-success">Вирішено</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 5px; flex-wrap: wrap;">
                                    @if($report->status == 'pending')
                                        <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Позначити вирішеним"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                    
                                    @if($report->type == 'comment')
                                        @php $comment = \App\Models\Comment::find($report->reference_id); @endphp
                                        @if($comment && $comment->user)
                                            @if($comment->user->isMuted())
                                                <button type="button" class="btn btn-sm btn-success" title="Зняти мут" onclick="openConfirmModal('{{ route('admin.users.unmute', $comment->user->id) }}', 'Зняти мут з цього користувача?', 'POST', 'btn-success', 'Зняти мут')">
                                                    <i class="fas fa-microphone"></i> Зняти мут
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-danger" onclick="openMuteModal({{ $comment->user->id }}, '{{ addslashes($comment->user->username) }}', '{{ addslashes($report->message) }}')" title="Мут автору коментаря">
                                                    <i class="fas fa-ban"></i> Мут
                                                </button>
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Кнопка Видалити скаргу -->
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Видалити скаргу" onclick="openConfirmModal('{{ route('admin.reports.destroy', $report->id) }}', 'Ви впевнені, що хочете видалити цю скаргу?', 'DELETE', 'btn-danger', 'Видалити')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Скарг у цій категорії поки немає.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>

<!-- Модальне вікно для Муту -->
<div id="muteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 450px; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);">
        <div class="card-header bg-danger">
            <h3 class="card-title text-white" style="margin-bottom: 0;"><i class="fas fa-ban"></i> Видати Мут: <span id="mute-username"></span></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" onclick="document.getElementById('muteModal').style.display = 'none'"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <form id="muteForm" action="" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Тривалість</label>
                    <div class="input-group">
                        <input type="number" name="mute_value" class="form-control" required min="1" placeholder="Кількість...">
                        <select name="mute_unit" class="form-control" style="max-width: 120px;">
                            <option value="seconds">Секунди</option>
                            <option value="minutes">Хвилини</option>
                            <option value="hours" selected>Години</option>
                            <option value="days">Дні</option>
                            <option value="weeks">Тижні</option>
                            <option value="months">Місяці</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label>Причина (буде показана користувачу)</label>
                    <select id="mute-reason-select" class="form-control mb-2" onchange="document.getElementById('mute-reason-input').value = this.value; if(!this.value) document.getElementById('mute-reason-input').focus();">
                        <option value="">-- Написати вручну --</option>
                        <option value="Спам / Реклама">Спам / Реклама</option>
                        <option value="Образа користувачів">Образа користувачів</option>
                        <option value="Нецензурна лексика">Нецензурна лексика</option>
                        <option value="Спойлер">Спойлер</option>
                        <option id="mute-reason-report-option" value="" style="display:none;"></option>
                    </select>
                    <input type="text" id="mute-reason-input" name="mute_reason" class="form-control" required placeholder="Напр. Спам, Образа">
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-default mr-2" onclick="document.getElementById('muteModal').style.display = 'none'">Скасувати</button>
                <button type="submit" class="btn btn-danger">Підтвердити</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальне вікно для підтвердження -->
<div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1060; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);">
        <div class="card-header bg-danger" id="confirmModalHeader">
            <h3 class="card-title text-white" style="margin-bottom: 0;"><i class="fas fa-exclamation-triangle"></i> Підтвердження дії</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" onclick="document.getElementById('confirmModal').style.display = 'none'"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <form id="confirmForm" action="" method="POST">
            @csrf
            <input type="hidden" name="_method" id="confirmMethod" value="POST">
            <div class="card-body">
                <p id="confirmMessage" style="margin: 0; font-size: 1.1rem; text-align: center;"></p>
            </div>
            <div class="card-footer text-center">
                <button type="button" class="btn btn-default mr-2" onclick="document.getElementById('confirmModal').style.display = 'none'">Скасувати</button>
                <button type="submit" class="btn" id="confirmBtnText">Підтвердити</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openMuteModal(userId, username, reportMessage = '') {
        document.getElementById('mute-username').innerText = username;
        
        let mainReason = reportMessage ? reportMessage.split('|')[0].trim() : '';
        let select = document.getElementById('mute-reason-select');
        let reportOption = document.getElementById('mute-reason-report-option');
        let input = document.getElementById('mute-reason-input');
        
        if (mainReason) {
            reportOption.value = mainReason;
            reportOption.innerText = 'Зі скарги: ' + mainReason;
            reportOption.style.display = 'block';
            select.value = mainReason;
            input.value = mainReason;
        } else {
            reportOption.style.display = 'none';
            select.value = '';
            input.value = '';
        }

        let formAction = '{{ route('admin.users.mute', ':id') }}';
        formAction = formAction.replace(':id', userId);
        document.getElementById('muteForm').action = formAction;
        document.getElementById('muteModal').style.display = 'flex';
    }

    function openConfirmModal(actionUrl, message, method = 'POST', btnClass = 'btn-danger', btnText = 'Підтвердити') {
        document.getElementById('confirmForm').action = actionUrl;
        document.getElementById('confirmMessage').innerText = message;
        document.getElementById('confirmMethod').value = method;
        
        let confirmBtn = document.getElementById('confirmBtnText');
        confirmBtn.className = 'btn ' + btnClass;
        confirmBtn.innerText = btnText;
        
        let header = document.getElementById('confirmModalHeader');
        if(btnClass === 'btn-success') {
            header.className = 'card-header bg-success';
        } else {
            header.className = 'card-header bg-danger';
        }

        document.getElementById('confirmModal').style.display = 'flex';
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleReportSearch');
        const searchContainer = document.getElementById('reportSearchContainer');
        const searchInput = document.getElementById('reportSearchInput');
        const closeBtn = document.getElementById('closeReportSearch');
        const filterForm = document.getElementById('reportFilterForm');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                if (!searchContainer.classList.contains('active')) {
                    e.preventDefault();
                    searchContainer.classList.add('active');
                    searchInput.focus();
                } else if (searchInput.value.trim() !== '') {
                    filterForm.submit();
                } else {
                    e.preventDefault();
                    searchContainer.classList.remove('active');
                }
            });

            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                if (window.location.search.includes('search=')) {
                    filterForm.submit();
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
@endsection
