<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Menu -->
   
    @include('partials.navbar')
    

    <!-- Étapes -->
    <div class="container">
        <div class="row all-content">
            <div class="col-4">
    <div class="steps">
    <div class="step completed" data-step="0">
            <div class="circle"><i class="fas fa-home"></i></div>
            <div class="text">Aperçu</div>
        </div>
        
        <div class="step active" data-step="1">
            <div class="circle">✔</div>
            <div class="text">Etapes du processus</div>
        </div>

        <div class="step" data-step="2">
            <div class="circle">✔</div>
            <div class="text">
                Évaluation des compétences</div>
        </div>
        <div class="step" data-step="3">
            <div class="circle">✔</div>
            <div class="text">Assiduité et disponibilité</div>
        </div>
        <div class="step" data-step="4">
            <div class="circle">✔</div>
            <div class="text">Fixation Des Objectifs Pour 2025/2026</div>
        </div>
        <div class="step" data-step="5">
            <div class="circle">✔</div>
            <div class="text">Opportunités d'évolution</div>
        </div>
        <div class="step" data-step="6">
            <div class="circle">✔</div>
            <div class="text">Appréciation du potentiel</div>
        </div>
        <div class="step" data-step="7">
            <div class="circle">✔</div>
            <div class="text">Signature et validation</div>
        </div>
        <div class="step" data-step="8">
            <div class="circle">✔</div>
            <div class="text">  Résultat</div>
        </div>
        
   
    </div>
   
   
   

   
    </div>

                                  


        <div class="col-8">
            
                 
                
        <!-- {{-- Check if user is logged in --}}
@if (session('user_id'))
    <p>Welcome, {{ session('user_id') }}</p>
    <p>Welcome, {{ session('name') }}</p>
    <p>Your Management ID: {{ session('management_id') }}</p>
@else
    <p>Please log in to continue.</p>
@endif

{{-- Optionally, show the success message --}}
@if (session('success'))
    <div>{{ session('success') }}</div>
@endif -->


            <div class="content  " id="step-1">
                <div class="container" style="padding-bottom:30px;">
    <div class="row"><h5>UEMF - Entretien de Progrès et de Développement 2024/2025</h5></div>
    <div class="row">
        <div class="img col-3">
            <img src="/img/example.jpeg" alt="">
        </div>
        <div class="col-6">
            <span class="spaced name">{{ $name }}</span><br>
            <span class="spaced para"></span><br>
            <span class="spaced para"><?php
// Get the current date formatted as "Month Day, Year"
echo date("F j, Y");
?></span>
        </div>
        <div class="col-3">
            <div class="progress-circle-container">
                <svg class="progress-circle" width="120" height="120">
                    <circle class="bg-circle" cx="60" cy="60" r="54"></circle>
                    <circle class="progress" cx="60" cy="60" r="54"></circle>
                </svg>
                <div class="progress-text">0%</div>
            </div>
        </div>
    </div>
</div>
                <div class="row"><div class="col-8"><span class="para2">L'évaluation annuelle de performance est un
                    acte managérial important. Elle s'appuie sur
                    un rendez-vous clé entre le manager et le
                    collaborateur: l'Entretien de Progrès et de
                    Développement. </span></div>
                   <div class="col-4 image-entretien"> <img src="/img/entretien.jpeg" alt=""></div>
                </div>
                <br>
                <span class="title1"> Etapes du processus</span> 
                <form action="{{ route('processus.submit.N1' , ['matricule' => $matricule, 'name' => $name]) }}" method="POST" id="form2">
                <div class="list">
                    <ol class="steps-wizard expand">
                        <li class='done' data-description="N">
                            <div class="options list-group">
                              <a href='#' class="list-group-item disabled">Auto-évaluation</a>
                            </div>
                          </li>
        
                      <li class='pending' data-description="N+1">
                        <div class="options list-group">
                          <a href='#' class="list-group-item disabled">Évaluation par le manager N+1</a>
                        </div>
                      </li>
                      <li class='pending' data-description="N+2">
                        <div class="options list-group">
                          <a href='#' class="list-group-item disabled">Évaluation par le manager N+2</a>
                        </div>
                      </li>
                    </ol>
                
                  </div>
                
                @csrf
              <div class="buttons">
                <button type="button" class="botton-second" onclick="window.location.href='{{ route('show.page1') }}'" >retour</button>
                <button type="submit" name="save_quit" class="botton-second">Enregistrer et quitter</button>
                <button type="submit" class="enregistrer-botton"  >enregistrer et continuer</button>
              </div>  
              </form>
            </div>
    
    
     
        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
    <script src="/js/file.js"></script>
    <script src="/https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
