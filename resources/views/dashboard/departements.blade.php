<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Departments - UEMF Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

  <style>
    .dept-row .dept-edit { display: none; }
    .dept-row.is-editing .dept-edit { display: flex; }
    .dept-row.is-editing .dept-view { display: none !important; }
  </style>
</head>
<body>

  <div class="main-wrapper">
    @include('components.sidebar')

    <div class="main-content">
      <div class="dashboard-container">

        <div class="page-header" data-aos="fade-down">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 data-en="Departments" data-fr="Départements">Departments</h1>
              <p class="text-muted mb-0" data-en="Add, rename or remove the departments employees belong to" data-fr="Ajouter, renommer ou supprimer les départements des collaborateurs">Add, rename or remove the departments employees belong to</p>
            </div>
            <a href="{{ route('admin.add-user') }}" class="btn btn-outline-secondary">
              <i class="fas fa-user-plus me-2"></i>
              <span data-en="Add User" data-fr="Ajouter Utilisateur">Add User</span>
            </a>
          </div>
        </div>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Add department -->
        <div class="dashboard-card mb-4" data-aos="fade-up">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-plus me-2"></i>
              <span data-en="New Department" data-fr="Nouveau Département">New Department</span>
            </h5>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.departements.store') }}" method="POST" class="row g-2 align-items-end">
              @csrf
              <div class="col-md-8">
                <label for="new_name" class="form-label" data-en="Name" data-fr="Nom">Name</label>
                <input type="text" id="new_name" name="name" class="form-control" maxlength="255" required
                       value="{{ old('_form') === 'create' ? old('name') : '' }}" placeholder="ex. DRH, DSI, Finance…">
                <input type="hidden" name="_form" value="create">
              </div>
              <div class="col-md-4 d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-plus me-2"></i><span data-en="Add" data-fr="Ajouter">Add</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Department list -->
        <div class="dashboard-card" data-aos="fade-up">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-building me-2"></i>
              <span data-en="All Departments" data-fr="Tous les Départements">All Departments</span>
              <span class="badge bg-secondary ms-2">{{ $departements->count() }}</span>
            </h5>
          </div>
          <div class="card-body">
            @if($departements->isEmpty())
              <p class="text-muted mb-0" data-en="No departments yet. Add the first one above." data-fr="Aucun département. Ajoutez le premier ci-dessus.">No departments yet. Add the first one above.</p>
            @else
              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th data-en="Name" data-fr="Nom">Name</th>
                      <th class="text-center" data-en="Employees" data-fr="Collaborateurs">Employees</th>
                      <th class="text-end" data-en="Actions" data-fr="Actions">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($departements as $dep)
                      <tr class="dept-row">
                        <td>
                          <span class="dept-view fw-semibold">{{ $dep->name }}</span>
                          <form action="{{ route('admin.departements.update', $dep->id) }}" method="POST" class="dept-edit gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $dep->name }}" maxlength="255" required aria-label="Nouveau nom">
                            <button type="submit" class="btn btn-sm btn-success" title="Enregistrer"><i class="fas fa-check"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit" title="Annuler"><i class="fas fa-times"></i></button>
                          </form>
                        </td>
                        <td class="text-center">
                          <span class="badge bg-info">{{ $dep->users_count }}</span>
                        </td>
                        <td class="text-end">
                          <div class="dept-view d-inline-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary js-edit" title="Renommer">
                              <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.departements.destroy', $dep->id) }}" method="POST"
                                  onsubmit="return confirm('Supprimer le département « {{ addslashes($dep->name) }} » ?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger"
                                      @if($dep->users_count > 0) disabled title="Des collaborateurs y sont rattachés" @else title="Supprimer" @endif>
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="{{ asset('js/sidebar.js') }}"></script>
  <script>
    AOS.init({ duration: 1000, once: true });

    document.querySelectorAll('.js-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const row = btn.closest('.dept-row');
        row.classList.add('is-editing');
        row.querySelector('.dept-edit input[name="name"]').focus();
      });
    });
    document.querySelectorAll('.js-cancel-edit').forEach(btn => {
      btn.addEventListener('click', () => btn.closest('.dept-row').classList.remove('is-editing'));
    });
  </script>
</body>
</html>
