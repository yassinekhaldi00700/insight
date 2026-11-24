<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Objectives - UEMF Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS (Animate On Scroll) -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Dashboard Custom CSS -->
  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>

  <div class="main-wrapper">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
      <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header text-center mb-4">
          <div class="row align-items-center">
            <div class="col-md-6 text-md-start">
              <h2>
                <i class="fas fa-target me-2"></i>
                <span data-en="Objectives Management" data-fr="Gestion des Objectifs">Objectives Management</span>
              </h2>
              <p class="text-muted" data-en="View and manage user objectives" data-fr="Voir et gérer les objectifs des utilisateurs">
                View and manage user objectives
              </p>
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-md-end gap-2">
                <button type="button" 
                        class="btn btn-primary" 
                        onclick="refreshData()"
                        title="Refresh Data">
                  <i class="fas fa-sync-alt me-1"></i>
                  <span data-en="Refresh" data-fr="Actualiser">Refresh</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Users with Objectives Table Section -->
        <div class="row">
          <div class="col-12" data-aos="fade-up" data-aos-delay="200">
            <div class="card dashboard-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">
                  <i class="fas fa-users"></i> 
                  <span data-en="Users with Objectives" data-fr="Utilisateurs avec Objectifs">Users with Objectives</span>
                  <span class="badge bg-primary ms-2" id="totalUsersCount">{{ count($usersWithObjectives ?? []) }}</span>
                </h5>
                <div class="table-filters d-flex gap-3">
                  <!-- Department Filter -->
                  <select id="departmentFilter" class="form-select form-select-sm" style="width: 200px;">
                    <option value="">All Departments</option>
                    @foreach($departments ?? [] as $dept)
                      <option value="{{ $dept->departement }}">{{ $dept->departement }}</option>
                    @endforeach
                  </select>
                  <!-- Search Filter -->
                  <input type="text" id="searchFilter" class="form-control form-control-sm" 
                         placeholder="Search users..." style="width: 250px;">
                </div>
              </div>
              
              <div class="table-responsive">
                <table class="table table-hover" id="usersTable" data-paginate="10">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Department</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="usersTableBody">
                    @forelse($usersWithObjectives ?? [] as $user)
                    <tr data-department="{{ $user->departement ?? '' }}" data-name="{{ strtolower($user->name ?? '') }}" data-email="{{ strtolower($user->email ?? '') }}">
                      <td>{{ $user->id }}</td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="user-avatar me-2">
                            <i class="fas fa-user-circle text-primary fa-2x"></i>
                          </div>
                          <div>
                            <strong>{{ $user->name ?? 'N/A' }}</strong>
                          </div>
                        </div>
                      </td>
                      <td>{{ $user->email ?? 'N/A' }}</td>
                      <td>
                        <span class="badge bg-secondary">{{ $user->departement ?? 'N/A' }}</span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-primary view-objectives-btn" 
                                data-user-id="{{ $user->id }}" 
                                data-user-name="{{ $user->name }}"
                                title="View Objectives">
                          <i class="fas fa-eye"></i> View Objectives
                        </button>
                      </td>
                    </tr>
                    @empty
                    <tr id="emptyState">
                      <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <h5>No users with objectives found</h5>
                        <p class="mb-0">Users with objectives will appear here when available.</p>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              
              <!-- No Results Message (Hidden by default) -->
              <div id="noResultsMessage" class="text-center text-muted py-4" style="display: none;">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h5>No matching results</h5>
                <p class="mb-0">Try adjusting your search criteria or filters.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Objectives Modal -->
  <div class="modal fade" id="objectivesModal" tabindex="-1" aria-labelledby="objectivesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="objectivesModalLabel">
            <i class="fas fa-target"></i> Objectives for <span id="modalUserName"></span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Qualitative Objectives -->
            <div class="col-md-6">
              <h6 class="mb-3">
                <i class="fas fa-comments text-info"></i> Qualitative Objectives
              </h6>
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <thead>
                    <tr>
                      <th>Objective</th>
                      <th>Description</th>
                      <th>Weight</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody id="qualitativeObjectives">
                    <!-- Will be populated by AJAX -->
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Quantitative Objectives -->
            <div class="col-md-6">
              <h6 class="mb-3">
                <i class="fas fa-chart-bar text-success"></i> Quantitative Objectives
              </h6>
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <thead>
                    <tr>
                      <th>Objective</th>
                      <th>Description</th>
                      <th>Quantity</th>
                      <th>Weight</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody id="quantitativeObjectives">
                    <!-- Will be populated by AJAX -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
  <!-- Sidebar JavaScript -->
  <script src="{{ asset('js/sidebar.js') }}"></script>
  
  <!-- Custom JavaScript -->
  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      offset: 100,
      once: true
    });

    // Language Toggle Functionality
    const languageToggle = document.getElementById('languageToggle');
    const currentLangSpan = document.getElementById('currentLang');
    
    let currentLanguage = localStorage.getItem('language') || 'en';
    updateLanguage(currentLanguage);
    
    languageToggle.addEventListener('click', function() {
      currentLanguage = currentLanguage === 'en' ? 'fr' : 'en';
      updateLanguage(currentLanguage);
      localStorage.setItem('language', currentLanguage);
    });
    
    function updateLanguage(lang) {
      const elements = document.querySelectorAll('[data-en][data-fr]');
      elements.forEach(element => {
        if (lang === 'fr') {
          element.textContent = element.getAttribute('data-fr');
        } else {
          element.textContent = element.getAttribute('data-en');
        }
      });
      currentLangSpan.textContent = lang.toUpperCase();
    }

    // Theme Toggle Functionality
    const themeToggle = document.getElementById('themeToggle');
    const lightIcon = document.getElementById('lightIcon');
    const darkIcon = document.getElementById('darkIcon');
    const body = document.body;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    body.setAttribute('data-theme', currentTheme);
    updateThemeIcons(currentTheme);
    
    themeToggle.addEventListener('click', function() {
      const newTheme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      body.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeIcons(newTheme);
    });
    
    function updateThemeIcons(theme) {
      if (theme === 'dark') {
        lightIcon.style.display = 'none';
        darkIcon.style.display = 'inline';
      } else {
        lightIcon.style.display = 'inline';
        darkIcon.style.display = 'none';
      }
    }

    // Filtering and Search Functionality
    const departmentFilter = document.getElementById('departmentFilter');
    const searchFilter = document.getElementById('searchFilter');
    const usersTableBody = document.getElementById('usersTableBody');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const totalUsersCount = document.getElementById('totalUsersCount');

    function filterTable() {
      const departmentValue = departmentFilter.value.toLowerCase();
      const searchValue = searchFilter.value.toLowerCase();
      const rows = usersTableBody.querySelectorAll('tr:not(#emptyState)');
      let visibleCount = 0;

      rows.forEach(row => {
        const department = row.getAttribute('data-department').toLowerCase();
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        
        const matchesDepartment = !departmentValue || department.includes(departmentValue);
        const matchesSearch = !searchValue || name.includes(searchValue) || email.includes(searchValue);
        
        if (matchesDepartment && matchesSearch) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      // Update count
      totalUsersCount.textContent = visibleCount;

      // Show/hide no results message
      const emptyState = document.getElementById('emptyState');
      if (visibleCount === 0 && rows.length > 0) {
        noResultsMessage.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';
      } else {
        noResultsMessage.style.display = 'none';
        if (emptyState && rows.length === 0) emptyState.style.display = '';
      }
    }

    departmentFilter.addEventListener('change', filterTable);
    searchFilter.addEventListener('input', filterTable);

    // Objectives Modal Functionality
    const objectivesModal = new bootstrap.Modal(document.getElementById('objectivesModal'));
    const viewObjectivesButtons = document.querySelectorAll('.view-objectives-btn');

    viewObjectivesButtons.forEach(button => {
      button.addEventListener('click', async function() {
        const userId = this.dataset.userId;
        const userName = this.dataset.userName;

        // Update modal title
        document.getElementById('modalUserName').textContent = userName;

        // Show loading state
        document.getElementById('qualitativeObjectives').innerHTML = '<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
        document.getElementById('quantitativeObjectives').innerHTML = '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';

        // Show modal
        objectivesModal.show();

        try {
          // Fetch objectives data
          const response = await fetch(`{{ route('dashboard.user.objectives') }}?user_id=${userId}`);
          const data = await response.json();

          // Populate qualitative objectives
          const qualitativeTable = document.getElementById('qualitativeObjectives');
          if (data.qualitative && data.qualitative.length > 0) {
            qualitativeTable.innerHTML = data.qualitative.map(obj => `
              <tr>
                <td><strong>${obj.objective || 'N/A'}</strong></td>
                <td>${obj.description || 'N/A'}</td>
                <td><span class="badge bg-info">${obj.weight || 'N/A'}</span></td>
                <td><small>${new Date(obj.created_at).toLocaleDateString()}</small></td>
              </tr>
            `).join('');
          } else {
            qualitativeTable.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No qualitative objectives found</td></tr>';
          }

          // Populate quantitative objectives
          const quantitativeTable = document.getElementById('quantitativeObjectives');
          if (data.quantitative && data.quantitative.length > 0) {
            quantitativeTable.innerHTML = data.quantitative.map(obj => `
              <tr>
                <td><strong>${obj.objective || 'N/A'}</strong></td>
                <td>${obj.description || 'N/A'}</td>
                <td><span class="badge bg-warning">${obj.quantity || 'N/A'}</span></td>
                <td><span class="badge bg-info">${obj.weight || 'N/A'}</span></td>
                <td><small>${new Date(obj.created_at).toLocaleDateString()}</small></td>
              </tr>
            `).join('');
          } else {
            quantitativeTable.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No quantitative objectives found</td></tr>';
          }

        } catch (error) {
          console.error('Error fetching objectives:', error);
          document.getElementById('qualitativeObjectives').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>';
          document.getElementById('quantitativeObjectives').innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
        }
      });
    });

    // Refresh Data Function
    function refreshData() {
      window.location.reload();
    }
  </script>
</body>
</html>
