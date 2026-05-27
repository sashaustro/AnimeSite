@extends('layouts.public')

@section('title', 'Увійти - AniHub')

@section('content')
<div class="auth-container">
    <h2>Увійти до аккаунту</h2>

    <!-- Session Status -->
    @if(session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Login (Email or Username) -->
        <div class="form-group">
            <label class="form-label" for="login">Електронна пошта або Нікнейм</label>
            <input id="login" class="form-control" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" />
            @error('login')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group mt-4">
            <label class="form-label" for="password">Пароль</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" class="form-label" style="margin-bottom: 0;">Зам'ятати мене</label>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            @if (Route::has('password.request'))
                <a style="font-size: 0.85rem; color: var(--text-muted);" href="{{ route('password.request') }}">
                    Забули пароль?
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                Увійти
            </button>
        </div>
    </form>
</div>
@endsection
