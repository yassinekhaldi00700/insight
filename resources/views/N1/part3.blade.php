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
            <div class="text">Signature et validation</div>
        </div>
        <div class="step" data-step="8">
            <div class="circle">✔</div>
            <div class="text">Résultat</div>
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
        <form id="objectives-form" method="POST" action="{{ route('fixation.submit.N1', ['matricule' => $matricule, 'name' => $name]) }}">
  @csrf
  
  <h2>Fixation Des Objectifs Pour 2025/2026</h2>
  <br>
  <span class="">Minimum 1 ligne et maximum 3 par section. La pondération totale doit être 100, répartie librement.</span>

  <!-- Qualitative Objectives -->
  <div>
    <label class="para1" style="font-size: 23px;">Objectifs Qualitatifs</label>
    <br>
    <div class="row align-items-center justify-content-center">
      <div class="col-5">
        <span class="para3">Ajouter un Objectif Qualitatif</span>
      </div>
      <div class="col-1">
        <button id="add-row-qualitatif" type="button" class="icon-add" style="background-image: url('{{ asset('img/add.svg') }}');"></button>
      </div>
    </div>

    <table id="table-qualitatif">
  <thead>
    <tr>
      <th>Objectif</th>
      <th>Description de l'objectif</th>
      <th>Pondération</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if($qualitativeObjectives->isNotEmpty())
      @foreach($qualitativeObjectives as $objective)
          <tr data-id="{{ $objective->id }}">
            <input type="hidden" value="{{ $objective->id }}" required>
            <td><input type="text" value="{{ $objective->objective }}" required></td>
            <td><input type="text" value="{{ $objective->description }}" required></td>
            <td><input type="number" value="{{ $objective->weight }}" class="pond-input" required></td>
            <td style="min-width: 110px;">
                <button class="add-btn icon" style="background-image: url('/img/confirm.svg');"></button>
                @if(isset($objective->id))
                  <button type="button" class="sa delete-btn icon js-delete-qualitative" data-url="{{ route('fixation.supprimer.N1', ['matricule' => $matricule, 'name' => $name, 'type' => 'qualitative', 'id' => $objective->id]) }}" style="background-image: url('/img/delete.svg');"></button>
                @else
                  <button class="delete-btn icon js-delete" style="background-image: url('/img/delete.svg');"></button>
                @endif
            </td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
    <!-- Hidden field to store qualitative objectives data -->
    <input type="hidden" id="qualitatif-data" name="qualitatif_data">
  </div>

  <!-- Quantitative Objectives -->
  <div>
    <label class="para1" style="font-size: 23px;">Objectifs Quantitatifs</label>
    <div class="row align-items-center justify-content-center">
      <div class="col-5">
        <span class="para3">Ajouter un Objectif Quantitatif</span>
      </div>
      <div class="col-1">
        <button id="add-row-quantitatif" type="button" class="icon-add" style="background-image: url('{{ asset('img/add.svg') }}');"></button>
      </div>
    </div>

    <table id="table-quantitatif">
  <thead>
    <tr>
      <th>Objectif</th>
      <th>Description de l'objectif</th>
      <th>Quantité</th>
      <th>Pondération</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if($quantitativeObjectives->isNotEmpty())
      @foreach($quantitativeObjectives as $objective1)
      <tr data-id="{{ $objective1->id }}">
           <input type="hidden" value="{{ $objective1->id }}" required>
            <td><input type="text" value="{{ $objective1->objective }}"required ></td>
            <td><input type="text" value="{{ $objective1->description }}" required></td>
            <td><input type="number" value="{{ $objective1->quantity }}" required></td>
            <td><input type="number" value="{{ $objective1->weight }}" min="1" max="100" placeholder="1-100" class="pond-input" required></td>
            <td style="min-width: 110px;">
                <button class="add-btn icon" style="background-image: url('/img/confirm.svg');"></button>
                @if(isset($objective1->id))
                  <button type="button" class="delete-btn icon js-delete-quantitative" data-url="{{ route('fixation.supprimer.N1', ['matricule' => $matricule, 'name' => $name, 'type' => 'quantitative', 'id' => $objective1->id]) }}" style="background-image: url('/img/delete.svg');"></button>
                @else
                  <button class="delete-btn icon js-delete" style="background-image: url('/img/delete.svg');"></button>
                @endif
            </td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
    <!-- Hidden field to store quantitative objectives data -->
    <input type="hidden" id="quantitatif-data" name="quantitatif_data">
  </div>

  <br>
  <span class=" warn  warning d-flex flex-row-reverse" ></span>
  <div class="buttons">
  <button type="button" class="botton-second" onclick="window.location.href='{{ route('partN12', ['matricule' => $matricule, 'name' => $name]) }}';">Retour</button>
    <button class="botton-second" name="save_quit" type="submit">Enregistrer et Quitter</button>
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

  // AJAX delete for qualitative objectives
  $(document).on('click', '.js-delete-qualitative', function(e) {
    e.preventDefault();
    const btn = $(this);
    const url = btn.data('url');
    const row = btn.closest('tr');
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        _token: $('input[name="_token"]').val()
      },
      success: function(response) {
        row.remove();
      },
      error: function(xhr) {
        alert('Erreur lors de la suppression.');
      }
    });
  });

  // AJAX delete for quantitative objectives
  $(document).on('click', '.js-delete-quantitative', function(e) {
    e.preventDefault();
    const btn = $(this);
    const url = btn.data('url');
    const row = btn.closest('tr');
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        _token: $('input[name="_token"]').val()
      },
      success: function(response) {
        row.remove();
      },
      error: function(xhr) {
        alert('Erreur lors de la suppression.');
      }
    });
  });
</script>
<script src="/js/file.js"></script>
<script src="/js/Fixation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/popup.js"></script>
</body>
</html>
