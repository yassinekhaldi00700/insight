{{-- Shared top navigation for the evaluation flow (N / N+1 / N+2) --}}
<header class="navbar app-navbar">
    <div class="container app-navbar__inner">
        <a href="{{ route('welcome') }}" class="logo" aria-label="UEMF Insights — Accueil">
            <img src="{{ asset('img/logo.png') }}" alt="UEMF Insights">
        </a>
        <nav class="app-navbar__actions" aria-label="Navigation principale">
            <a href="{{ route('welcome') }}" class="svg-navbar" title="Accueil" aria-label="Accueil">
                <img src="{{ asset('img/home.svg') }}" alt="">
            </a>
            @if (session('user_id'))
            <form method="POST" action="{{ route('logout') }}" class="app-navbar__form" data-no-loading>
                @csrf
                <button type="submit" class="app-navbar__btn" title="Logout">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Logout</span>
                </button>
            </form>
            @endif
        </nav>
    </div>
</header>
