@extends('layouts.admin')

@section('title', 'Користувачі')
@section('page_title', 'Керування користувачами')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Список всіх користувачів</h3>
    </div>
    
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ім'я</th>
                    <th>Нікнейм</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username ?? '—' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Адміністратор</span>
                            @else
                                <span class="badge badge-secondary">Користувач</span>
                            @endif
                        </td>
                        <td>
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
    
    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
