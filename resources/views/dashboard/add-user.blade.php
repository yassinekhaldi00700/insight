<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add User - UEMF Dashboard</title>

  <!-- ✅ Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ AOS (Animate On Scroll) -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- ✅ Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- ✅ Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- ✅ Dashboard Custom CSS -->
  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

  <style>
    /* Manager Selection Styling */
    .manager-selection {
      display: flex;
      gap: 20px;
      align-items: center;
      margin-top: 8px;
    }
    
    .manager-option {
      position: relative;
    }
    
    .manager-option input[type="radio"] {
      position: absolute;
      opacity: 0;
      width: 1px;
      height: 1px;
    }

    .manager-option input[type="radio"]:focus-visible + .manager-label {
      box-shadow: 0 0 0 .2rem rgba(102, 126, 234, 0.35);
      border-color: #667eea;
    }
    
    .manager-label {
      display: flex;
      align-items: center;
      padding: 10px 22px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border: 2px solid #dee2e6;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      min-width: 120px;
      justify-content: center;
    }
    
    .manager-label:hover {
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      border-color: #667eea;
    }
    
    .manager-option input[type="radio"]:checked + .manager-label {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: #667eea;
      color: #fff;
    }

    .manager-option input[type="radio"]:checked + .manager-label i {
      color: #fff !important;
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    /* Dark mode manager selection */
    body.dark-theme .manager-label {
      background: #252b3a;
      border-color: #364152;
      color: #fff;
    }
    
    body.dark-theme .manager-label:hover {
      border-color: #667eea;
    }
    
    body.dark-theme .manager-option input[type="radio"]:checked + .manager-label {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: #667eea;
    }
  </style>

</head>
<body>

  <div class="main-wrapper">
    <!-- Sidebar -->
    @include('components.sidebar')
    <!-- Main Content -->
    <div class="main-content">
      <div class="dashboard-container">
        
        <!-- Add User Form Content -->
        <div class="page-header" data-aos="fade-down">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 data-en="Add New User" data-fr="Ajouter Nouvel Utilisateur">Add New User</h1>
              <p class="text-muted mb-0" data-en="Create a new user account with supervisor assignment" data-fr="Créer un nouveau compte utilisateur avec attribution de superviseur">Create a new user account with supervisor assignment</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-2"></i>
              <span data-en="Back to Dashboard" data-fr="Retour au Tableau de bord">Back to Dashboard</span>
            </a>
          </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-in">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-in">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <!-- Add User Form Card -->
        <div class="dashboard-card" data-aos="fade-up">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-user-plus me-2"></i>
              <span data-en="User Information" data-fr="Informations Utilisateur">User Information</span>
            </h5>
          </div>
          
          <div class="card-body">
            <form action="{{ route('admin.store-user') }}" method="POST">
              @csrf
              
              <div class="row">
                <!-- Name Field -->
                <div class="col-md-6 mb-3">
                  <label for="name" class="form-label">
                    <span data-en="Full Name" data-fr="Nom Complet">Full Name</span> <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
                  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Email Field -->
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">
                    <span data-en="Email Address" data-fr="Adresse Email">Email Address</span> <span class="text-danger">*</span>
                  </label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Department Field -->
                <div class="col-md-6 mb-3">
                  <label for="departement_id" class="form-label d-flex justify-content-between align-items-center">
                    <span><span data-en="Department" data-fr="Département">Department</span> <span class="text-danger">*</span></span>
                    <a href="{{ route('admin.departements') }}" class="small text-decoration-none">
                      <i class="fas fa-plus me-1"></i><span data-en="Manage departments" data-fr="Gérer les départements">Manage departments</span>
                    </a>
                  </label>
                  <select class="form-select @error('departement_id') is-invalid @enderror" id="departement_id" name="departement_id" required>
                    <option value="" data-en="Select Department" data-fr="Sélectionner Département">Select Department</option>
                    @foreach($departments as $department)
                      <option value="{{ $department->id }}" {{ old('departement_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->departement }}
                      </option>
                    @endforeach
                  </select>
                  @error('departement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Supervisor Field -->
                <div class="col-md-6 mb-3">
                  <label for="supervisor_id" class="form-label">
                    <span data-en="Supervisor" data-fr="Superviseur">Supervisor</span>
                  </label>
                  <select class="form-select" id="supervisor_id" name="supervisor_id">
                    <option value="" data-en="No Supervisor" data-fr="Aucun Superviseur">No Supervisor</option>
                    @foreach($supervisors as $supervisor)
                      <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->name }} - {{ $supervisor->departement }}
                      </option>
                    @endforeach
                  </select>
                </div>
                
                <!-- Manager Selection -->
                <div class="col-12 mb-4">
                  <label class="form-label">
                    <span data-en="Is Manager?" data-fr="Est Manager?">Is Manager?</span> <span class="text-danger">*</span>
                  </label>
                  <div class="manager-selection">
                    <div class="manager-option">
                      <input type="radio" name="is_manager" id="manager_yes" value="yes" {{ old('is_manager') == 'yes' ? 'checked' : '' }} required>
                      <label class="manager-label" for="manager_yes">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span data-en="Yes" data-fr="Oui">Yes</span>
                      </label>
                    </div>
                    <div class="manager-option">
                      <input type="radio" name="is_manager" id="manager_no" value="no" {{ old('is_manager') == 'no' ? 'checked' : '' }} required>
                      <label class="manager-label" for="manager_no">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        <span data-en="No" data-fr="Non">No</span>
                      </label>
                    </div>
                  </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="col-12">
                  <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                      <i class="fas fa-times me-2"></i>
                      <span data-en="Cancel" data-fr="Annuler">Cancel</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-user-plus me-2"></i>
                      <span data-en="Create User" data-fr="Créer Utilisateur">Create User</span>
                    </button>
                  </div>
                </div>
                
                <div class="col-12 mt-3">
                  <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    <span data-en="Default password will be set to 'password123'. User will be notified to change it on first login." data-fr="Le mot de passe par défaut sera défini sur 'password123'. L'utilisateur sera notifié de le changer lors de la première connexion.">Default password will be set to 'password123'. User will be notified to change it on first login.</span>
                  </small>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ✅ Bootstrap 5 JavaScript Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ AOS (Animate On Scroll) -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <!-- ✅ Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <!-- ✅ Centralized Sidebar JS -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    // Initialize AOS
    AOS.init({
      duration: 1000,
      once: true
    });

    // Add-user page specific functionality
    // Sidebar functionality (theme toggle, language toggle) is handled by sidebar.js

    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
      const requiredFields = form.querySelectorAll('[required]');
      let hasErrors = false;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.classList.add('is-invalid');
          hasErrors = true;
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (hasErrors) {
        e.preventDefault();
        alert(currentLanguage === 'fr' ? 'Veuillez remplir tous les champs obligatoires.' : 'Please fill in all required fields.');
      }
    });

    // Remove invalid class on input
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
      input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
      });
    });
  </script>

</body>
</html>
