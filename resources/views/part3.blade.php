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
    <style>
        .text-red-600 {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}
    </style>
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

        <div class="step active" data-step="4">
            <div class="circle">✔</div>
            <div class="text">Opportunités d'évolution</div>
        </div>
        
        <div class="step" data-step="5">
            <div class="circle">✔</div>
            <div class="text">Signature et validation</div>
        </div>
        <div class="step" data-step="6">
            <div class="circle">✔</div>
            <div class="text">Résultat</div>
        </div>
        
   
    </div>
   
   
   

   
    </div>

                                    <!-- page1 -->


    

                                   

                                <!-- page 2 -->




    


                                <!-- page 3 -->



        <div class="col-8">
            
                 
                
              

           
    



                                        <!-- page 4 -->




    



                            <!-- page 5 -->


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
                                                    <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 135.717px;"></circle>
                                                </svg>
                                                <div class="progress-text">60%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <h2>Opportunités d'evolution</h2>
                                @if ($errors->has('error'))
                                    <div class="warning">
                                        {{ $errors->first('error') }}
                                    </div>
                                @endif
                       <form action="{{ route('opportunité.submit') }}" method="POST" id="form1">
                        @csrf
                         @error('court_terme')
                            <div class="text-red-600 error-message">{{ $message }}</div>
                        @enderror

                        <div class="section">
                            <div class="question">Souhaits de carrière à Court / Moyen Terme (Réservé au collaborateur)</div>
                            <textarea name="court_terme" id="court_terme" rows="2">{{ old('court_terme', $oldCourtTerme) }}</textarea>
                        </div>
                         @error('long_terme')
                            <div class="text-red-600 error-message">{{ $message }}</div>
                        @enderror
                        <div class="section">
                            <div class="question">Souhaits de carrière à Long terme (Réservé au collaborateur)</div>
                            <textarea name="long_terme" id="long_terme" rows="2">{{ old('long_terme', $oldLongTerme) }}</textarea>
                        </div>
                        <div class="buttons">
                            <button type="button" class="botton-second" onclick="window.location.href='{{ route('part2') }}';">Retour</button>
                            <button type="submit" name="save_quit" class="botton-second">Enregistrer et quitter</button>
                            <button type="submit" class="enregistrer-botton">Enregistrer et continuer</button>
                        </div>
                    </form>
                                
                                
                            </div>
   




                                                 <!-- page 6 -->






                                    <!-- page 7 -->





        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
                    <script>
                document.getElementById("form1").addEventListener("submit", function(e) {
                    function wordCount(text) {
                        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
                    }

                    const court = document.getElementById("court_terme").value;
                    const longt = document.getElementById("long_terme").value;

                    if (wordCount(court) < 3) {  // minimum 5 words (change if needed)
                        alert("Veuillez écrire au moins 3 mots pour le Court/Moyen Terme.");
                        e.preventDefault();
                        return;
                    }

                    if (wordCount(longt) < 3) {
                        alert("Veuillez écrire au moins 3 mots pour le Long Terme.");
                        e.preventDefault();
                    }
                });

                   document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            // Select all error messages and hide them
            document.querySelectorAll('.error-message').forEach(function(el) {
                el.style.display = 'none';
            });
        }, 5000); // 5000 ms = 5 seconds
    });
                </script>
     
    <script src="file.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
