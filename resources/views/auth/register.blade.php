<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — UEMF Insights</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main class="auth-shell">
        <section class="auth-panel" aria-labelledby="auth-title">
            <img class="auth-logo" src="{{ asset('img/logo.png') }}" alt="UEMF Insights">
            <h2 class="auth-title" id="auth-title">Register</h2>
            <p class="auth-subtitle">Créez votre compte UEMF Insights</p>

            <form class="auth-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    </div>
                    @error('name')<p class="field-error" id="name-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </div>
                    @error('email')<p class="field-error" id="email-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        <input id="password" type="password" name="password" autocomplete="new-password" required
                               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    </div>
                    @error('password')<p class="field-error" id="password-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                    </div>
                </div>

                <button type="submit" class="login-button">Register</button>
            </form>

            <p class="register-link">Déjà un compte ? <a href="{{ route('login') }}">Login</a></p>
        </section>

        <section class="right-panel" aria-hidden="true">
            <h1>Bienvenue à <br> L'UEMF INSIGHTS</h1>
            <p>Identifiez-vous pour accéder à votre compte</p>
            <div class="brand-badges">
                <span>Auto-évaluation</span>
                <span>Évaluation N+1</span>
                <span>Évaluation N+2</span>
            </div>
        </section>
    </main>
    <script src="{{ asset('js/ui.js') }}"></script>
</body>
</html>
