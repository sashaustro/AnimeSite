@extends('layouts.admin')

@section('title', 'Скарги - Адмін-панель')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="card-title m-0 font-weight-bold text-primary">Скарги</h3>
    </div>
    
    <div class="card-body bg-light border-bottom py-3">
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
                                                <form action="{{ route('admin.users.unmute', $comment->user->id) }}" method="POST" class="m-0" onsubmit="return confirm('Зняти мут з цього користувача?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Зняти мут">
                                                        <i class="fas fa-microphone"></i> Зняти мут
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-danger" onclick="openMuteModal({{ $comment->user->id }}, '{{ addslashes($comment->user->username) }}', '{{ addslashes($report->message) }}')" title="Мут автору коментаря">
                                                    <i class="fas fa-ban"></i> Мут
                                                </button>
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Кнопка Видалити скаргу -->
                                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цю скаргу?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Видалити скаргу"><i class="fas fa-trash"></i></button>
                                    </form>
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
</script>
@endsection
