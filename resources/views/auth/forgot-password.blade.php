@extends('layouts.public')

@section('title', 'Відновлення пароля - AniHub')

@section('content')
<div class="auth-container">
    <h2>Відновлення пароля</h2>

    <div class="mb-4 text-sm" style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">
        Забули пароль? Не проблема. Просто введіть свою електронну адресу, і ми надішлемо вам посилання для створення нового пароля.
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="alert-success" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Електронна пошта</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                Надіслати посилання
            </button>
        </div>
    </form>
</div>
@endsection
