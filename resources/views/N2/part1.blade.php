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

        <div class="step active" data-step="2">
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
            <div class="text">Synthèse</div>
        </div>
        <div class="step" data-step="8">
            <div class="circle">✔</div>
            <div class="text">Signature et validation</div>
        </div>
        
   
    </div>
   
   
   

   
    </div>

                                    <!-- page1 -->


    

                                   

                                <!-- page 2 -->




    


                                <!-- page 3 -->



        <div class="col-8">
            
                 
                
              

        <!-- {{-- Check if user is logged in --}}
@if (session('user_id'))
    <p>Welcome, {{ session('user_id') }}</p>
    <p>Your Management ID: {{ session('management_id') }}</p>
@else
    <p>Please log in to continue.</p>
@endif

{{-- Optionally, show the success message --}}
@if (session('success'))
    <div>{{ session('success') }}</div>
@endif -->

    <div class="content" id="step-1">
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
                            <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 290.822px;"></circle>
                        </svg>
                        <div class="progress-text">29%</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="radio-group">
            <label class="radio-button">
                <input type="radio" disabled  name="option1" value="0">
                <span>0 Débutant</span>
            </label>
            <label class="radio-button">
                <input type="radio" disabled  name="option1" value="1">
                <span>1 Élémentaire</span>
            </label>
            <label class="radio-button">
                <input type="radio" disabled  name="option1" value="2">
                <span>2 Intermédiaire</span>
            </label>
            <label class="radio-button">
                <input type="radio" disabled  reqh  name="option1" value="3">
                <span>3 Avancé</span>
            </label>
            <label class="radio-button">
                <input type="radio" disabled  name="option1" value="4">
                <span>4 Expert</span>
            </label>
        </div>
        @if ($errors->has('error'))
            <div class="warning">
            {{ $errors->first('error') }}
            </div>
        @endif
        <form action="{{ route('questions.submit.N2', ['matricule' => $matricule, 'name' => $name,'id'=>$id]) }}" method="POST" id="form2">
    @csrf

    <h2 style="color:#23627f">Maitrise de l'environnement général (Externe et Interne)</h2>
    <br>

    @php $totalQuestions = 0; @endphp <!-- Initialize counter -->

    @foreach ($categories as $category_name => $questions)
    <div class="content-rep">
        <!-- Display Category Name -->
        <h4 class="titre">{{ $category_name }}</h4>
        <br>
        
        <!-- Display Questions Under the Category -->
        @foreach ($questions as $question)
            <div class="question">
                <p>{{ $question->question_text }}</p>
                <div class="radio-group">
                    @for ($i = 0; $i <= 4; $i++)
                        <label class="radio-button">
                            <input 
                                type="radio" 
                                name="responses[{{ $question->question_id }}]" 
                                value="{{ $i }}" 
                                @if (isset($responses[$question->question_id]) && $responses[$question->question_id] == $i) checked @endif>
                            <span>{{ $i }}</span>
                        </label>
                    @endfor
                </div>
                <input type="hidden" name="categories[{{ $question->question_id }}]" value="{{ $question->categorie_id }}">
            </div>
            <div class="box"> 
                <div class="row position-image">
                    <div class="col-2 position-image">
                        <div class="image-small"><img src="/img/example.jpeg" alt=""></div>
                    </div>
                    <div class="col-10">
                        <span class="para1"> {{ $username }} </span>
                        <span class="para"> (Responsable) Évalué: <span class="para1">{{ $responses1[$question->question_id]->response_value ?? 'N/A' }} / 4</span>  Évaluation UEMF 2024/2025 </span>
                        <span class="para"> Date: {{ $responses1[$question->question_id]->updated_at ?? 'N/A' }} </span>
                    </div>
                </div>
            </div>
            <div class="box"> 
                <div class="row position-image">
                    <div class="col-2 position-image">
                        <div class="image-small"><img src="/img/example.jpeg" alt=""></div>
                    </div>
                    <div class="col-10">
                        <span class="para1"> {{ $name }} </span>
                        <span class="para"> (Collaborateur) Évalué: <span class="para1">{{ $responses2[$question->question_id]->response_value ?? 'N/A' }} / 4 </span> Évaluation UEMF 2024/2025 </span>
                        <span class="para"> Date: {{ $responses2[$question->question_id]->updated_at ?? 'N/A' }} </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <br>
@endforeach

    
    <input type="hidden" name="total_questions" value="{{ $totalQuestions }}">

    <br>

    <div class="buttons">
        <button type="button" class="botton-second" onclick="window.location.href='{{ route('partN20', ['matricule' => $matricule, 'name' => $name,'id' => $id]) }}';">retour</button>
        <button type="submit" name="save_quit" class="botton-second">Enregistrer et quitter</button>
        <button type="submit" class="enregistrer-botton">enregistrer et continuer</button>
    </div> 
</form>



        
    </div> 

    



                                        <!-- page 4 -->




   
    
     
        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
    <script src="file.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
