@extends('layouts.public')

@section('title', 'Створення нового пароля - AniHub')

@section('content')
<div class="auth-container">
    <h2>Створення нового пароля</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Електронна пошта</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            @error('email')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group mt-4">
            <label class="form-label" for="password">Новий пароль</label>
            <div style="position: relative;">
                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" style="padding-right: 40px;" />
                <button type="button" onclick="togglePassword('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 5px;">
                    <i class="far fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group mt-4">
            <label class="form-label" for="password_confirmation">Підтвердження пароля</label>
            <div style="position: relative;">
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" style="padding-right: 40px;" />
                <button type="button" onclick="togglePassword('password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 5px;">
                    <i class="far fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                Зберегти пароль
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
