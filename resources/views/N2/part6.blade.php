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
            <div class="text">Fixation Des Objectifs Pour 2025/2026</div>
        </div>
        <div class="step completed" data-step="5">
            <div class="circle">✔</div>
            <div class="text">Opportunités d'évolution</div>
        </div>
        <div class="step completed" data-step="6">
            <div class="circle">✔</div>
            <div class="text">Appréciation du potentiel</div>
        </div>
        <div class="step active" data-step="7">
            <div class="circle">✔</div>
            <div class="text">Synthèse</div>
        </div>
        <div class="step " data-step="8">
            <div class="circle">✔</div>
            <div class="text">Signature et validation</div>
        </div>
        
   
    </div>
   
   
   

   
    </div>

                                    <!-- page1 -->


    

                                   

                                <!-- page 2 -->




    


                                <!-- page 3 -->



        <div class="col-8">
            
                 
                
              


                                        <!-- page 8 -->


                                        <div class="content" id="step-1">
        <div class="container" style="padding-bottom:30px;">
            <div class="row"><h5>UEMF - Entretien de Progrès et de Développement 2024/2025</h5></div>
            <div class="row">
                <div class="img col-3">
                    <img src="/img/example.jpeg" alt="">
                </div>
                <div class="col-6">
                    <span class="spaced name">{{$name}}</span><br>
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
                            <circle class="progress" cx="60" cy="60" r="54" style="stroke-dashoffset: 48.4703px;"></circle>
                        </svg>
                        <div class="progress-text">86%</div>
                    </div>
                </div>
            </div>
        </div>
        <span class="title1">Synthèse</span>
        <br>
        <span class="warning">Répond à toutes les attentes</span>
        <span class="info" title="Synthèse">i</span>
        <table>
            <thead>
                <tr>
                    <th> </th>
                    <th>Auto-évaluation</th>
                    <th>Évaluation par le Manager N+1</th>
                    <th>Évaluation par le Manager N+2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Évaluation des compétences</td>
                    <td>{{ number_format($result, 2) }} / 4<br>4 - Maîtrise 3e</td>
                    <td>{{ number_format($result1, 2) }} / 4<br>3 - Maîtrise confirmée</td>
                    <td>{{ number_format($result2, 2) }} / 4<br>3 - Maîtrise confirmée</td>
                </tr>
                <tr>
                    <td>Assiduité et Disponibilité</td>
                    <td>{{$responses->average}} / 4<br>
                    @if ($responses->average == '1')
    Ne répond pas aux attentes
@elseif ($responses->average == '2' || $responses->average == '1.5')
    Répond partiellement aux attentes
@elseif ($responses->average == '3' || $responses->average == '2.5')
    Répond à l'ensemble des attentes
@elseif ($responses->average == '4' || $responses->average == '3.5')
    Nettement supérieure aux attentes
@endif</td>
                    <td>{{$responses1->average}} / 4<br>
                    @if ($responses1->average == '1')
    Ne répond pas aux attentes
@elseif ($responses1->average == '2' || $responses1->average == '1.5')
    Répond partiellement aux attentes
@elseif ($responses1->average == '3' || $responses1->average == '2.5')
    Répond à l'ensemble des attentes
@elseif ($responses1->average == '4' || $responses1->average == '3.5')
    Nettement supérieure aux attentes
@endif
                </td>
                    <td>{{$responses2->average }} / 4<br>
                    @if ($responses2->average == '1')
    Ne répond pas aux attentes
@elseif ($responses2->average == '2' || $responses2->average == '1.5')
    Répond partiellement aux attentes
@elseif ($responses2->average == '3' || $responses2->average == '2.5')
    Répond à l'ensemble des attentes
@elseif ($responses2->average == '4' || $responses2->average == '3.5')
    Nettement supérieure aux attentes
@endif</td>
                </tr>
                
            </tbody>
          
    <tr class="average-row">
        <td>Moyenne <span class="info" title="Calculée comme la moyenne des trois scores.">i</span></td>
        <td>
            {{ number_format($finalresult->resultn0, 2) }} / 4<br>
            {{ ($result + $responses->average) / 2 >= 3 ? "Répond à toutes les attentes" : "Répond partiellement aux attentes" }}
        </td>
        <td>
            {{ number_format($finalresult->resultn1, 2) }} / 4<br>
            {{ ($result1 + $responses1->average) / 2 >= 3 ? "Répond à toutes les attentes" : "Répond partiellement aux attentes" }}
        </td>
        <td>
            {{ number_format($finalresult->resultn2, 2) }} / 4<br>
            {{ ($result2 + $responses2->average) / 2 >= 3 ? "Répond à toutes les attentes" : "Répond partiellement aux attentes" }}
        </td>
    </tr>
     


        </table>
        <table>
            <tbody>
                 <tfoot>
                    <form action="{{ route('result.submit.N2' ,['matricule' => $matricule, 'name' => $name,'id'=> $id]) }}" method="POST" >
        @csrf
         <tr>
                    <td> Résultat final <span class="info" title="calculé  sur la base de 20 % pour  N0, 40 % pour N1 et 40 % pour N2.">i</span></td>
                    <td><div class="final-result-box">
  <p>{{ number_format($finalresult->final_result, 2) }}</p>
</div></td>
<td>
     @if(is_null($finalresult->addedvalue))
    <input type="number" 
           name="addedvalue_present" 
           class="number-input" 
           min="-0.5" 
           max="0.5" 
           step="0.05" 
           value="0.00">
    <button type="submit" name="soumettre" class="button-submit-05">soumettre</button>
@else
    <input type="number" 
           class="number-input" 
           value="{{ $finalresult->addedvalue }}" 
           disabled>
@endif
</td>
                 </tr>
</tfoot>
            </tbody>
        </table>
        

    
        
        
          <br><br>
         
         
           
            
          <button type="button" class="botton-second" onclick="window.location.href='{{ route('partN25', ['matricule' => $matricule, 'name' => $name,'id' => $id]) }}';">Retour</button>
            <button class="botton-second" name="save_quit" type="submit" >enregistrer et quitter</button>
            <button class="enregistrer-botton" type="submit">enregistrer et continuer</button></div>
    </div>
    </form>
     
        
    </div>
    </div>
    </div>
    </div>
    @include('footer') 
    <script src="file.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
