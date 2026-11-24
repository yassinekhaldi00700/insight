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
    <link rel="stylesheet" href="/css/style.css">
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
       
        <div class="step active" data-step="3">
            <div class="circle">✔</div>
            <div class="text">Assiduité et disponibilité</div>
        </div>

        <div class="step" data-step="4">
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




    <div class="content " id="step-3">
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
                            <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 203.575px;"></circle>
                        </svg>
                        <div class="progress-text">40%</div>
                    </div>
                </div>
            </div>
        </div>
           <span class="title1"> Assiduité et Disponibilité</span> <br>
           <div class="row"><div class="col-8"><span class="para2">Le collaborateur apprécie son niveau d'assiduité sur l'année passée. Lors de l'Entretien de Progrès de et
            Développement, le Manager confirme et/ou modifie ladite appréciation. </span></div>
           <div class="col-4 image-entretien"> <img src="/img/entretien.jpeg" alt=""></div>
        </div>
           
             <br> <br>
             <form action="{{ route('Assiduité.submit') }}" method="POST" id="form2">
    @csrf

    <span class="title1">Assiduité</span><br>
    <select name="assiduite" id="assiduite" required>
        <option value="" disabled {{ empty($oldAssiduite) ? 'selected' : '' }}></option>
        
        <option value="1" {{ $oldAssiduite == 1 ? 'selected' : '' }}>Ne répond pas aux attentes</option>
        <option value="2" {{ $oldAssiduite == 2 ? 'selected' : '' }}>Répond partiellement aux attentes</option>
        <option value="3" {{ $oldAssiduite == 3 ? 'selected' : '' }}>Répond à l'ensemble des attentes</option>
        <option value="4" {{ $oldAssiduite == 4 ? 'selected' : '' }}>Nettement supérieure aux attentes</option>
    </select>
    <br><br>

    <span class="title1">Disponibilité</span><br>
    <select name="disponibilite" id="disponibilite" required>
        <option value="" disabled {{ empty($oldDisponibilite) ? 'selected' : '' }}></option>
        
        <option value="1" {{ $oldDisponibilite == 1 ? 'selected' : '' }}>Ne répond pas aux attentes</option>
        <option value="2" {{ $oldDisponibilite == 2 ? 'selected' : '' }}>Répond partiellement aux attentes</option>
        <option value="3" {{ $oldDisponibilite == 3 ? 'selected' : '' }}>Répond à l'ensemble des attentes</option>
        <option value="4" {{ $oldDisponibilite == 4 ? 'selected' : '' }}>Nettement supérieure aux attentes</option>
    </select>
    <br><br>

    <div class="buttons">
        <button type="button" class="botton-second" onclick="window.location.href='{{ route('part1') }}';">Retour</button>
        <button type="submit" name="save_quit" class="botton-second">Enregistrer et quitter</button>
        <button type="submit" class="enregistrer-botton">Enregistrer et continuer</button>
    </div>
</form>



                            <!-- page 5 -->


   




                                                 <!-- page 6 -->






                                    <!-- page 7 -->




        
    
                                    
    
     
        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
    <script src="file.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
