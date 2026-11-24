<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progression par étapes</title>
    <!-- Inclusion Bootstrap 4 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclusion jQuery -->
    <script src="/img/https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Inclusion Bootstrap JS -->
    <script src="/img/https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
        <div class="step active" data-step="4">
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

    <div class="col-8">


   

    <div class="content " id="step-1">
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
                            <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 193.881px;"></circle>
                        </svg>
                        <div class="progress-text">43%</div>
                    </div>
                </div>
            </div>
        </div>
        <form id="objectives-form" method="POST" action="{{ route('fixation.submit.N2', ['matricule' => $matricule, 'name' => $name,'id' => $id]) }}">
  @csrf
  
  <h2>Fixation Des Objectifs Pour 2025/2026</h2>
  <br>
  

  <!-- Qualitative Objectives -->
  <div>
    <label class="para1" style="font-size: 23px;">Objectifs Qualitatifs</label>
    <br>
    <table id="table-qualitatif" style="max-width:700px;">
  <thead>
    <tr>
      <th>Objectif</th>
      <th>Description de l'objectif</th>
      <th>Pondération</th>
      
    </tr>
  </thead>
  <tbody>
    @if($qualitativeObjectives->isNotEmpty())
      @foreach($qualitativeObjectives as $objective)
          <tr>
            <td>{{ $objective->objective }}</td>
            <td>{{ $objective->description }}</td>
            <td>{{ $objective->weight }}</td>
            
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
    
  </div>

  <!-- Quantitative Objectives -->
  <div>
    <label class="para1" style="font-size: 23px;">Objectifs Quantitatifs</label>
   

    <table id="table-quantitatif" style="max-width:700px;">
  <thead>
    <tr>
      <th>Objectif</th>
      <th>Description de l'objectif</th>
      <th>Quantité</th>
      <th>Pondération</th>
      
    </tr>
  </thead>
  <tbody>
    @if($quantitativeObjectives->isNotEmpty())
      @foreach($quantitativeObjectives as $objective)
      <tr>
            <td>{{ $objective->objective }}</td>
            <td>{{ $objective->description }}</td>
            <td>{{ $objective->quantity }}</td>
            <td>{{ $objective->weight }}</td>
            
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
    
  </div>

  <br>
  <span class=" warn  warning d-flex flex-row-reverse" ></span>
  <div class="buttons">
  <button type="button" class="botton-second" onclick="window.location.href='{{ route('partN22', ['matricule' => $matricule, 'name' => $name,'id' => $id]) }}';">Retour</button>
    <button class="botton-second" type="submit" name="save_quit">Enregistrer et Quitter</button>
    <button class="enregistrer-botton" id="next-stepp"  type="submit">Enregistrer et Continuer</button>
  </div>
</form>
      
                                            
                                        </div>
</div>
</div>
</div>


@include('footer') 
<script>

    const confirm = "{{ asset('img/confirm.svg') }}";
    const editIconUrl = "{{ asset('img/edit.svg') }}";
    const deleteIconUrl = "{{ asset('img/delete.svg') }}";

   
</script>
<script src="/js/file.js"></script>
<script src="/js/Fixation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/popup.js"></script>
</body>
</html>
