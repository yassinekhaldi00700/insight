<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UEMF Insights</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main class="auth-shell">
        <!-- Form panel -->
        <section class="auth-panel" aria-labelledby="auth-title">
            <img class="auth-logo" src="{{ asset('img/logo.png') }}" alt="UEMF Insights">
            <h2 class="auth-title" id="auth-title">Login</h2>
            <p class="auth-subtitle">Identifiez-vous pour accéder à votre compte</p>

            <div aria-live="polite">
                @if ($errors->any())
                    <div class="auth-alert auth-alert--error" role="alert">
                        <svg viewBox="0 0 20 20" width="18" height="18" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-8-4a1 1 0 0 0-1 1v3a1 1 0 1 0 2 0V7a1 1 0 0 0-1-1Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if (session('success'))
                    <div class="auth-alert auth-alert--success" role="status">
                        <svg viewBox="0 0 20 20" width="18" height="18" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
            </div>

            <form class="auth-form" action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="Votre adresse email" autocomplete="email" required autofocus
                               @if($errors->any()) aria-invalid="true" @endif>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap has-toggle">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" autocomplete="current-password" required
                               @if($errors->any()) aria-invalid="true" @endif>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Show password" aria-controls="password" aria-pressed="false" id="toggleBtn">
                            <img id="toggleIcon" src="/img/eye-closed.svg" alt="">
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-button" id="loginButton">Login</button>
            </form>
        </section>

        <!-- Brand panel -->
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

    <script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleBtn = document.getElementById('toggleBtn');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.src = '/img/eye.svg'; // Switch to "eye" icon
            toggleBtn.setAttribute('aria-label', 'Hide password');
            toggleBtn.setAttribute('aria-pressed', 'true');
        } else {
            passwordField.type = 'password';
            toggleIcon.src = '/img/eye-closed.svg'; // Switch to "eye-closed" icon
            toggleBtn.setAttribute('aria-label', 'Show password');
            toggleBtn.setAttribute('aria-pressed', 'false');
        }
    }
    </script>
    <script src="{{ asset('js/ui.js') }}"></script>
</body>
</html>
