<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employees - UEMF Dashboard</title>

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

</head>
<body>

  <div class="main-wrapper">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
      <div class="dashboard-container">
        
        <!-- Page Header -->
        <div class="page-header">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 data-en="Employees Management" data-fr="Gestion des Employés">Employees Management</h1>
              <p class="text-muted mb-0" data-en="View and manage all employees in the system" data-fr="Voir et gérer tous les employés du système">View and manage all employees in the system</p>
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('admin.add-user') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>
                <span data-en="Add Employee" data-fr="Ajouter Employé">Add Employee</span>
              </a>
              <button id="exportBtn" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>
                <span data-en="Export Excel" data-fr="Exporter Excel">Export Excel</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <!-- Filters Card -->
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-filter me-2"></i>
              <span data-en="Filters & Search" data-fr="Filtres et Recherche">Filters & Search</span>
            </h5>
          </div>
          
          <div class="card-body">
            <div class="row">
              <!-- Search Input -->
              <div class="col-md-4 mb-3">
                <label for="search" class="form-label">
                  <span data-en="Search" data-fr="Rechercher">Search</span>
                </label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Search by name or email...">
              </div>
              
              <!-- Department Filter -->
              <div class="col-md-3 mb-3">
                <label for="department" class="form-label">
                  <span data-en="Department" data-fr="Département">Department</span>
                </label>
                <select class="form-select" id="department" name="department">
                  <option value="" data-en="All Departments" data-fr="Tous les Départements">All Departments</option>
                  @foreach($departments as $department)
                    <option value="{{ $department->departement }}">
                      {{ $department->departement }}
                    </option>
                  @endforeach
                </select>
              </div>
              
              <!-- Manager Filter -->
              <div class="col-md-3 mb-3">
                <label for="manager" class="form-label">
                  <span data-en="Manager Status" data-fr="Statut Manager">Manager Status</span>
                </label>
                <select class="form-select" id="manager" name="manager">
                  <option value="" data-en="All" data-fr="Tous">All</option>
                  <option value="1" data-en="Managers Only" data-fr="Managers Seulement">Managers Only</option>
                  <option value="0" data-en="Non-Managers" data-fr="Non-Managers">Non-Managers</option>
                </select>
              </div>
              
              <!-- Clear Button -->
              <div class="col-md-2 mb-3">
                <label class="form-label">&nbsp;</label>
                <div>
                  <button type="button" id="clearFilters" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>
                    <span data-en="Clear" data-fr="Effacer">Clear</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Employees Table Card -->
        <div class="dashboard-card">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                <span data-en="Employees List" data-fr="Liste des Employés">Employees List</span>
                <span class="badge bg-primary ms-2" id="employeeCount">{{ count($employees) }}</span>
              </h5>
            </div>
          </div>
          
          <div class="card-body p-0">
            <div id="employeeTableContainer">
              @if(count($employees) > 0)
                <div class="table-responsive">
                  <table class="table table-hover mb-0" id="employeeTable" data-paginate="10">
                    <thead class="table-dark">
                      <tr>
                        <th data-en="ID" data-fr="ID">ID</th>
                        <th data-en="Name" data-fr="Nom">Name</th>
                        <th data-en="Email" data-fr="Email">Email</th>
                        <th data-en="Department" data-fr="Département">Department</th>
                        <th data-en="Manager" data-fr="Manager">Manager</th>
                        <th data-en="Supervisor" data-fr="Superviseur">Supervisor</th>
                        <th data-en="Created Date" data-fr="Date de Création">Created Date</th>
                        <th data-en="Actions" data-fr="Actions">Actions</th>
                      </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                      @foreach($employees as $employee)
                        <tr>
                          <td>{{ $employee->id }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar-sm me-2">
                                <i class="fas fa-user"></i>
                              </div>
                              <strong>{{ $employee->name }}</strong>
                            </div>
                          </td>
                          <td>{{ $employee->email }}</td>
                          <td>
                            <span class="badge bg-info">{{ $employee->departement ?? 'N/A' }}</span>
                          </td>
                          <td>
                            @if($employee->management_id)
                              <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                <span data-en="Yes" data-fr="Oui">Yes</span>
                              </span>
                            @else
                              <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>
                                <span data-en="No" data-fr="Non">No</span>
                              </span>
                            @endif
                          </td>
                          <td>{{ $employee->supervisor_name ?? 'No Supervisor' }}</td>
                          <td>{{ date('Y-m-d', strtotime($employee->created_at)) }}</td>
                          <td>
                            <div class="btn-group btn-group-sm">
                              <button class="btn btn-outline-primary" 
                                      onclick="viewEmployee({{ $employee->id }})" 
                                      title="View Employee Details">
                                <i class="fas fa-eye"></i>
                              </button>
                              <button class="btn btn-outline-warning js-change-password"
                                      data-id="{{ $employee->id }}" data-name="{{ $employee->name }}"
                                      title="Changer le mot de passe">
                                <i class="fas fa-key"></i>
                              </button>
                              <button class="btn btn-outline-danger"
                                      onclick="deleteEmployee({{ $employee->id }}, '{{ $employee->name }}')"
                                      title="Delete Employee">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-5" id="noEmployeesMessage">
                  <i class="fas fa-users fa-3x text-muted mb-3"></i>
                  <h5 class="text-muted" data-en="No employees found" data-fr="Aucun employé trouvé">No employees found</h5>
                  <p class="text-muted" data-en="Try adjusting your search criteria or add new employees" data-fr="Essayez d'ajuster vos critères de recherche ou d'ajouter de nouveaux employés">Try adjusting your search criteria or add new employees</p>
                  <a href="{{ route('admin.add-user') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    <span data-en="Add First Employee" data-fr="Ajouter Premier Employé">Add First Employee</span>
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ✅ Bootstrap 5 JavaScript Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ AOS (Animate On Scroll) -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
  <!-- ✅ Centralized Sidebar JS -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    // Initialize without AOS animations
    let allEmployees = @json($employees);

    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
      const search = document.getElementById('search').value;
      const department = document.getElementById('department').value;
      const manager = document.getElementById('manager').value;
      
      const params = new URLSearchParams({
        search: search,
        department: department,
        manager: manager
      }).toString();
      
      window.location.href = '{{ route("admin.employees.export") }}' + (params ? '?' + params : '');
    });

    // Simple filtering function
    function filterEmployees() {
      const search = document.getElementById('search').value.toLowerCase();
      const department = document.getElementById('department').value;
      const manager = document.getElementById('manager').value;

      let filteredEmployees = allEmployees.filter(employee => {
        const nameMatch = employee.name.toLowerCase().includes(search);
        const emailMatch = employee.email.toLowerCase().includes(search);
        const searchMatch = search === '' || nameMatch || emailMatch;
        
        const departmentMatch = department === '' || employee.departement === department;
        
        let managerMatch = true;
        if (manager === '1') {
          managerMatch = employee.management_id == 1;
        } else if (manager === '0') {
          managerMatch = employee.management_id == 0 || employee.management_id == null;
        }

        return searchMatch && departmentMatch && managerMatch;
      });

      updateTable(filteredEmployees);
    }

    // Update table content
    function updateTable(employees) {
      const tableContainer = document.getElementById('employeeTableContainer');
      const employeeCount = document.getElementById('employeeCount');
      
      employeeCount.textContent = employees.length;

      if (employees.length === 0) {
        tableContainer.innerHTML = `
          <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No employees found</h5>
            <p class="text-muted">Try adjusting your search criteria or add new employees</p>
            <a href="{{ route('admin.add-user') }}" class="btn btn-primary">
              <i class="fas fa-user-plus me-2"></i>Add First Employee
            </a>
          </div>
        `;
        return;
      }

      let tableHTML = `
        <div class="table-responsive">
          <table class="table table-hover mb-0" data-paginate="10">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Manager</th>
                <th>Supervisor</th>
                <th>Created Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
      `;

      employees.forEach(employee => {
        const managerBadge = employee.management_id ? 
          '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Yes</span>' :
          '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>No</span>';
          
        const createdDate = new Date(employee.created_at).toLocaleDateString('en-CA');
        
        tableHTML += `
          <tr>
            <td>${employee.id}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar-sm me-2">
                  <i class="fas fa-user"></i>
                </div>
                <strong>${employee.name}</strong>
              </div>
            </td>
            <td>${employee.email}</td>
            <td>
              <span class="badge bg-info">${employee.departement || 'N/A'}</span>
            </td>
            <td>${managerBadge}</td>
            <td>${employee.supervisor_name || 'No Supervisor'}</td>
            <td>${createdDate}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" 
                        onclick="viewEmployee(${employee.id})" 
                        title="View Employee Details">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-warning js-change-password"
                        data-id="${employee.id}" data-name="${String(employee.name).replace(/"/g, '&quot;')}"
                        title="Changer le mot de passe">
                  <i class="fas fa-key"></i>
                </button>
                <button class="btn btn-outline-danger"
                        onclick="deleteEmployee(${employee.id}, '${employee.name}')"
                        title="Delete Employee">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      });

      tableHTML += `
            </tbody>
          </table>
        </div>
      `;

      tableContainer.innerHTML = tableHTML;
    }

    // Search input - simple debounce
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(filterEmployees, 300);
    });

    // Filter dropdowns - immediate filter
    document.getElementById('department').addEventListener('change', filterEmployees);
    document.getElementById('manager').addEventListener('change', filterEmployees);

    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
      document.getElementById('search').value = '';
      document.getElementById('department').value = '';
      document.getElementById('manager').value = '';
      filterEmployees();
    });

    // View Employee Function
    function viewEmployee(employeeId) {
      // Create employee details modal
      const employee = @json($employees).find(emp => emp.id === employeeId);
      if (!employee) return;

      const modalHtml = `
        <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">
                  <i class="fas fa-user me-2"></i>Employee Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">ID:</label>
                      <p class="mb-0">${employee.id}</p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Name:</label>
                      <p class="mb-0">${employee.name}</p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Email:</label>
                      <p class="mb-0">${employee.email}</p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Department:</label>
                      <p class="mb-0">${employee.departement || 'N/A'}</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Manager Status:</label>
                      <p class="mb-0">
                        ${employee.management_id ? 
                          '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Yes</span>' : 
                          '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>No</span>'
                        }
                      </p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Supervisor:</label>
                      <p class="mb-0">${employee.supervisor_name || 'No Supervisor'}</p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Created Date:</label>
                      <p class="mb-0">${new Date(employee.created_at).toLocaleDateString()}</p>
                    </div>
                    <div class="info-group mb-3">
                      <label class="fw-bold text-muted">Last Updated:</label>
                      <p class="mb-0">${new Date(employee.updated_at).toLocaleDateString()}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="fas fa-times me-2"></i>Close
                </button>
              </div>
            </div>
          </div>
        </div>
      `;

      // Remove existing modal if any
      const existingModal = document.getElementById('employeeModal');
      if (existingModal) {
        existingModal.remove();
      }

      // Add modal to body
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
      modal.show();

      // Clean up modal after it's hidden
      document.getElementById('employeeModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
      });
    }

    // Make viewEmployee function globally available
    window.viewEmployee = viewEmployee;

    // Delete Employee Function
    function deleteEmployee(employeeId, employeeName) {
      // Create confirmation modal
      const modalHtml = `
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                  <i class="fas fa-exclamation-triangle me-2"></i>Delete Employee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="text-center">
                  <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                  <h5>Are you sure you want to delete this employee?</h5>
                  <p class="text-muted mb-0">
                    <strong>${employeeName}</strong><br>
                    <small>This action cannot be undone and will permanently remove all employee data.</small>
                  </p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete(${employeeId})">
                  <i class="fas fa-trash me-2"></i>Delete Employee
                </button>
              </div>
            </div>
          </div>
        </div>
      `;

      // Remove existing modal if any
      const existingModal = document.getElementById('deleteModal');
      if (existingModal) {
        existingModal.remove();
      }

      // Add modal to body
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();

      // Clean up modal after it's hidden
      document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
      });
    }

    // Confirm Delete Function
    function confirmDelete(employeeId) {
      // Create and submit delete form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/admin/employees/' + employeeId + '/delete';
      
      // Add CSRF token
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';
      form.appendChild(csrfToken);
      
      // Add method spoofing for DELETE
      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'DELETE';
      form.appendChild(methodInput);
      
      // Add form to body and submit
      document.body.appendChild(form);
      form.submit();
    }

    // Make functions globally available
    window.deleteEmployee = deleteEmployee;
    window.confirmDelete = confirmDelete;
  </script>

  <!-- Change password modal -->
  <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="POST" id="passwordForm" class="modal-content">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="passwordModalLabel">
            <i class="fas fa-key me-2"></i>Changer le mot de passe
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Nouveau mot de passe pour <strong id="passwordUserName"></strong></p>
          <div class="mb-3">
            <label for="new_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="new_password" name="password" minlength="8" required autocomplete="new-password">
            <div class="form-text">8 caractères minimum.</div>
          </div>
          <div class="mb-2">
            <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="password_confirmation" minlength="8" required autocomplete="new-password">
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="showPasswords">
            <label class="form-check-label" for="showPasswords">Afficher</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Enregistrer</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function () {
      const modalEl = document.getElementById('passwordModal');
      const form = document.getElementById('passwordForm');
      const pwd = document.getElementById('new_password');
      const pwd2 = document.getElementById('new_password_confirmation');

      // Works for both server-rendered and JS-rendered rows
      document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-change-password');
        if (!btn) return;
        form.action = '{{ url('/admin/employees') }}/' + btn.dataset.id + '/password';
        document.getElementById('passwordUserName').textContent = btn.dataset.name;
        form.reset();
        pwd.type = pwd2.type = 'password';
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
      });

      modalEl.addEventListener('shown.bs.modal', () => pwd.focus());

      document.getElementById('showPasswords').addEventListener('change', function () {
        pwd.type = pwd2.type = this.checked ? 'text' : 'password';
      });

      form.addEventListener('submit', function (e) {
        if (pwd.value !== pwd2.value) {
          e.preventDefault();
          pwd2.setCustomValidity('Les mots de passe ne correspondent pas.');
          pwd2.reportValidity();
        }
      });
      pwd2.addEventListener('input', () => pwd2.setCustomValidity(''));
    })();
  </script>

</body>
</html>
