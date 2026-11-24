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
        <div class="step completed" data-step="1">
            <div class="circle">✔</div>
            <div class="text">Etapes du processus</div>
        </div>
        <div class="step completed" data-step="2">
            <div class="circle">✔</div>
            <div class="text">
                Évaluation des compétences</div>
        </div>
       
        <div class="step completed" data-step="3">
            <div class="circle">✔</div>
            <div class="text">Assiduité et disponibilité</div>
        </div>

        <div class="step completed" data-step="4">
            <div class="circle">✔</div>
            <div class="text">Opportunités d'évolution</div>
        </div>
        
        <div class="step completed" data-step="5">
            <div class="circle">✔</div>
            <div class="text">Signature et validation</div>
        </div>
        <div class="step active" data-step="6">
            <div class="circle">✔</div>
            <div class="text">Résultat</div>
        </div>
        
   
    </div>
   
   
   

   
    </div>

                                    <!-- page1 -->


    

                                   

                                <!-- page 2 -->




    


                                <!-- page 3 -->



        <div class="col-8">
            
                 
                
              


                                        <!-- page 8 -->


    <div class="content " id="step-1">
        <div class="container" style="padding-bottom:30px;">
            <div class="row"><h5>UEMF - Entretien de Progrès et de Développement 2024/2025</h5></div>
            <div class="row">
                <div class="img col-3">
                    <img src="/img/example.jpeg" alt="">
                </div>
                <div class="col-6">
                    <span class="spaced name">{{ session('name') }}</span><br>
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
                            <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 0px;"></circle>
                        </svg>
                        <div class="progress-text">100%</div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('result.submit') }}" method="POST" id="form1">
    @csrf
    <div class="row justify-content-center text-center">
        <h2>Votre résultat est</h2>
        <h1 class="display-1">{{ number_format($result, 2) }}/4</h1>
        <input type="hidden" name="result" value="{{ $result }}"> 
    </div>
    <br><br>
    <div class="row d-flex flex-row-reverse">
        <div class="col-3">
            <button type="submit" class="enregistrer-botton">Terminer</button>
        </div>
        <div class="col-2">
            <button type="button" class="botton-second" onclick="window.location.href='{{ route('part4') }}';">Retour</button>
        </div>
    </div>
</form>

        
    </div>
    
     
        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
    <script src="file.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
