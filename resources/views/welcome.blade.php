<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil — UEMF Insights</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/welcome.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
</head>
<body>
    @include('partials.navbar')

    <main class="container welcome-main">
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-12 col-lg-7">
                <div class="welcome-hero">
                    <div class="image-small"><img src="/images/{{ session('image') }}" alt=""></div>
                    <h1 class="titre1">Bonjour <span class="titre">{{ session('name') }},</span></h1>
                </div>
                <p class="welcome-intro">
                    Bienvenue sur la plateforme UEMF Insights ! <br>
                    Nous vous invitons à remplir le formulaire d'évaluation avec attention, afin de contribuer à un processus transparent et constructif.
                </p>

                <div class="welcome-tiles">
                    <div class="first-box">
                        <span class="titre2">Capital Humain</span>
                        <p class="text1">en chiffres.</p>
                    </div>
                    <div class="second-box">
                        <span class="titre2">Diversité Culturelle</span>
                        <p class="text1">Plus de ... Nationalités.</p>
                    </div>
                    <div class="third-box">
                        <span class="titre2">Parité de Genre</span>
                        <p class="text1">Plus de .... collaboratrices bénéficiant de plusieurs avantages sociaux.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="welcome-panel">
                    @if(isset($hideButton) && $hideButton)
                        @if(isset($result))
                            <div class="welcome-result">
                                <h2>Votre résultat est :</h2>
                                <p class="display-1">{{ number_format($result, 2) }}/4</p>
                            </div>
                        @else
                            <p class="text-center text-muted mb-0">Aucun résultat trouvé.</p>
                        @endif
                    @else
                        <button type="button" class="simple-btn" onclick="window.location.href='{{ route('show.page') }}'">
                            <span>Commencer mon auto-évaluation</span>
                            <span class="simple-btn__arrow" aria-hidden="true">➡</span>
                        </button>
                    @endif

                    @if(session('isSupervisor'))
                        <button type="button" class="simple-btn" onclick="window.location.href='{{ route('show.page1') }}'">
                            <span>Gérer Mon Équipe</span>
                            <span class="simple-btn__arrow" aria-hidden="true">➡</span>
                        </button>
                    @endif

                    @if(session('is_admin'))
                        <button type="button" class="simple-btn" onclick="window.location.href='{{ route('dashboard') }}'">
                            <span>Tableau de bord (Admin)</span>
                            <span class="simple-btn__arrow" aria-hidden="true">➡</span>
                        </button>
                    @endif

                    {{-- @if(session('isSupervisorOfSupervisor'))
                        <button type="button" class="simple-btn" onclick="window.location.href='{{ route('liste') }}'">
                            <span>Gérer Les Superviseurs</span>
                            <span class="simple-btn__arrow" aria-hidden="true">➡</span>
                        </button>
                    @endif --}}

                    <div class="welcome-panel__images">
                        <div class="image-euromed"><img src="/img/Success.jpg" alt=""></div>
                        <div class="image-euromed"><img src="/img/euromed.jpg" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('footer')
</body>
</html>
