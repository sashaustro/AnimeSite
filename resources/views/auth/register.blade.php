@extends('layouts.public')

@section('title', 'Реєстрація - AniHub')

@section('content')
<div class="auth-container">
    <h2>Створити аккаунт</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label class="form-label" for="name">Ім'я (як до вас звертатись)</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Username -->
        <div class="form-group mt-4">
            <label class="form-label" for="username">Нікнейм (для логіну)</label>
            <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" />
            @error('username')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group mt-4">
            <label class="form-label" for="email">Електронна пошта</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group mt-4">
            <label class="form-label" for="password">Пароль</label>
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
            <label class="form-label" for="password_confirmation">Підтвердження паролю</label>
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

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <a style="font-size: 0.85rem; color: var(--text-muted);" href="{{ route('login') }}">
                Вже зареєстровані?
            </a>

            <button type="submit" class="btn btn-primary">
                Реєстрація
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
