<footer class="footer">
    <div class="container footer__inner">
        <a href="{{ route('welcome') }}" class="logo" aria-label="UEMF Insights — Accueil">
            <img src="{{ asset('img/logo.png') }}" alt="UEMF Insights">
        </a>
        <div class="solve">
            <p class="foot">Privacy Policy</p>
            <p class="foot">Terms Of Service</p>
        </div>
        <div class="footer__social">
            <a href="https://www.instagram.com/ueuromed/" target="_blank" rel="noopener" aria-label="Instagram">
                <img src="{{ asset('img/instagram.svg') }}" alt="">
            </a>
            <a href="https://www.facebook.com/UniversiteEuromed" target="_blank" rel="noopener" aria-label="Facebook">
                <img src="{{ asset('img/facebook.svg') }}" alt="">
            </a>
            <a href="https://www.linkedin.com/school/universit-euro-m-diterran-enne-de-f-s/" target="_blank" rel="noopener" aria-label="LinkedIn">
                <img src="{{ asset('img/linkedin.svg') }}" alt="">
            </a>
        </div>
        <p class="footer__copy">&copy; {{ date('Y') }} Université Euromed de Fès — UEMF Insights</p>
    </div>
</footer>
<script src="{{ asset('js/ui.js') }}"></script>
