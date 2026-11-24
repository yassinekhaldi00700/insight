<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Progression par étapes</title>
    <!-- Inclusion Bootstrap 4 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclusion jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Inclusion Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Inclusion FontAwesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/progress-circle.css">
    <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js" type="text/javascript"></script>
    
</head>
<body>
    

   
    @include('partials.navbar')
<div class="container page-toolbar">
    <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('welcome') }}'"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Retour</button>
</div>
<div class="container" >
    <div class="row align-items-center justify-content-center g-3 mt-1 mb-4"><div class="col-12 col-md-8 col-lg-9"><p class="hello"> M. <span class="name">{{ session('name') }},</span> <br>Bienvenue sur votre tableau de bord !</p></div><div class="col-12 col-md-4 col-lg-3 d-flex justify-content-md-end"><div class="ms-md-3">
        <div class="block">
            <div class="box">
                <p class="number">
                    <span class="num">{{ $dashboardData['percentageStatusFaite'] }}</span>
                    <span class="sub">%</span>
                  
                </p>
                
            </div>
            <span class="dots"></span>
            <svg class="svg">
                <circle class="circle" cx="90" cy="90" r="80" />
            </svg>
        </div> </div></div>
    
</div>
<br>
<div class="container">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="dash-numbers rounded d-flex align-items-center justify-content-between p-4">
                <div class="dash-image"><img src="/img/all.svg" alt=""></div>
                <div class="ms-3">
                    <p class="dash-text">Collaborateurs</p>
                    <h6 class="dash-text">{{ $dashboardData['totalUsers'] }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class=" dash-numbers rounded d-flex align-items-center justify-content-between p-4">
                <div class="dash-image"><img src="/img/dash-N.svg" alt=""></div>
                <div class="ms-3">
                    <p class="dash-text">Auto-évaluations</p>
                    <h6 class="dash-text">{{ $dashboardData['totalStatusFaite'] }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class=" dash-numbers rounded d-flex align-items-center justify-content-between p-4">
                <div class="dash-image"><img src="/img/dash-N1.svg" alt=""></div>
                <div class="ms-3">
                    <p class="dash-text">Mes réponses</p>
                    <h6 class="dash-text">{{ $dashboardData['totalActionFaite'] }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
        
            <div class=" dash-numbers rounded d-flex align-items-center justify-content-between p-4">
                <div class="dash-image"><img src="/img/dash-N1.svg" alt=""></div>
                <div class="ms-3">
                    <p class="dash-text">RDV confirmés</p>
                    <h6 class="dash-text">1</h6>
                </div>
            </div>
                
                
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row all-content">
        
        <div class="col-12">
<h2> Détails-Gestion de l'équipe</h2>
<table>
    <tr>
        <th>Matricule</th>
        <th>Nom et Prénom</th>
        <th>Auto-évaluation</th>
        <th>Etat</th>
        <th>Fixer la Date</th>
        <th>Date</th>
        @if(session('isSupervisorOfSupervisor'))
        <th>leur equipe</th>
        @endif
    </tr>
    @foreach ($userDetails as $user)
        <tr>
            <td>{{ $user['matricule'] }}</td>
            <td>{{ $user['equipe'] }}</td>
            <td>
                <div class="{{ $user['status'] == 'Realisée' ? 'complete' : 'pas-encore' }}">
                    {{ ucfirst($user['status']) }}
                </div>
            </td>
            <td>
            @if ($user['action'] === 'remplir') 
    <button
        class="Remplir-button"
        id="next-step"
        onclick="window.location.href='{{ route('remplir', ['matricule' => $user['matricule'], 'name' => $user['equipe']]) }}';">
        Remplir
    </button>
@elseif ($user['action'] === 'Realisée')
    <div class="complete">Realisée</div>
@else
    <div class="pas-encore">Non Realisée</div>
@endif
</td>
<td>
    <button
        class="show-popup date-button"
        data-matricule="{{ $user['matricule'] }}"> <!-- Here you are passing the matricule -->
        {{ ucfirst($user['fixer_date_action']) }}
    </button> 
</td>
            <td>{{ $user['fixer_date_label'] }}</td>
            @if(session('isSupervisorOfSupervisor'))
                
            <td>
    @if ($user['is_supervisor'])
        <button onclick="window.location.href='{{ route('show.page2', ['id' => $user['matricule']]) }}'"
            class="Remplir-button">
            View Equipe
        </button>
        @else
        -
    @endif
</td>
            @endif

        </tr>
    @endforeach
</table>
</div>
</div>
</div>
<div id="popup" role="dialog" aria-modal="true" aria-label="Fixer la date de l'entretien" style="display: none;">
        
            

<form action="{{ route('fixer-date') }}" method="POST" id="dateForm">
    @csrf
    <!-- Hidden input to store matricule -->
    <input type="hidden" name="matricule" id="matricule-input">

    <div class="warning">
        Les champs comportant un "*" sont obligatoires. Prière de bien les renseigner avant d'avancer vers l'étape suivante.
    </div>

    <div class="question">Avez-vous planifié un RDV d'entretien avec votre collaborateur ? *</div>
    <select name="has_rdv" required>
        <option value="oui">Oui</option>
        <option value="non">Non</option>
    </select>

    <div class="question">Veuillez préciser la date de l'entretien d'évaluation convenue avec votre collaborateur *</div>
    <input type="date" name="rdv_date" value="2024-12-08" required>

    <div class="question">Veuillez également indiquer l'heure de l'entretien *</div>
    <input type="time" name="rdv_time" value="14:00" required>

    <div class="button-group">
        <button type="submit" class="enregistrer-botton">Enregistrer</button>
        <button type="button" id="close-popup" class="botton-second">Fermer</button>
    </div>
</form>
    
</div>
<div id="overlay"></div>
    
    


@include('footer') 

<script src="/js/pie.js"></script>
<script src="/js/popup.js"></script>
</body>
</html>