<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ asset('css/welcome.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
</head>
<body>
    <!-- Menu -->
   
    @include('partials.navbar')

    
    <!-- {{-- Check if user is logged in --}}
@if (session('user_id'))
    <p>Welcome, {{ session('user_id') }}</p>
    
    <p>Your Management ID: {{ session('management_id') }}</p>
@else
    <p>Please log in to continue.</p>
@endif -->
<div class="container">
    <div class="row align-items-center justify-content-center">
    <div class="col-9"><span class="titre1">Bonjour  <span class="titre">{{ session('name') }}</span> </span></div>
    <br><br><br><br>
    <span class="titre1">Bienvenue sur notre plateforme !  Nous vous invitons à remplir vos informations pour personnaliser votre expérience et avancer dans le processus.</span>
    
<table  style="width: 75%; text-align: center; margin-top:100px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    
    <tbody>
        @if(!empty($teamMembers) && count($teamMembers) > 0)
            @foreach($teamMembers as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>
                        <button onclick="window.location.href='{{ route('show.page2', ['id' => $member->id]) }}'"
                         class="Remplir-button">
                            View Equipe
                        </button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">No team members found.</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>
           
            <br><br>

    @include('footer')
</body>
</html>