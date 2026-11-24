<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Assiduité & Disponibilité</title>

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

      <!-- Page Header -->
      <div class="dashboard-container">
        <div class="dashboard-header text-center mb-4">
          <div class="row align-items-center">
            <div class="col-12 col-md-8 text-md-start">
              <h2 data-en="Attendance &amp; Availability" data-fr="Assiduité &amp; Disponibilité">
                <i class="fas fa-calendar-check text-primary me-2"></i>Attendance &amp; Availability
              </h2>
              <p class="text-muted mb-0"
                 data-en="Overview of attendance and availability scores across all evaluation levels"
                 data-fr="Aperçu des scores d'assiduité et disponibilité sur tous les niveaux d'évaluation">
                Overview of attendance and availability scores across all evaluation levels
              </p>
            </div>
            <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
              <button class="btn btn-outline-primary btn-sm" onclick="toggleAssiduiteTable()" id="assiduiteToggleBtn">
                <i class="fas fa-table me-1"></i>
                <span data-en="Show Details" data-fr="Voir Détails">Show Details</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ============================================================
           MAIN SECTION
      ============================================================ -->
      <div class="dashboard-container mt-4" data-aos="fade-up" data-aos-delay="100">

        @php
          $ratingLabels = [
            0 => ['label'=>'—',                              'class'=>'secondary'],
            1 => ['label'=>'Ne répond pas',                  'class'=>'danger'],
            2 => ['label'=>'Partiellement',                  'class'=>'warning'],
            3 => ['label'=>'Répond aux attentes',             'class'=>'primary'],
            4 => ['label'=>'Nettement supérieure',            'class'=>'success'],
          ];
          $roles = [
            'n0' => ['label'=>'N0 (Auto-évaluation)', 'icon'=>'fa-user',        'color'=>'#00bfa5'],
            'n1' => ['label'=>'N+1 (Manager)',         'icon'=>'fa-user-tie',    'color'=>'#667eea'],
            'n2' => ['label'=>'N+2 (Supérieur)',       'icon'=>'fa-user-shield', 'color'=>'#e53935'],
          ];
        @endphp

        <!-- ── Role Average Cards ── -->
        <div class="row g-3 mb-4">
          @foreach($roles as $roleKey => $roleMeta)
            @php $rd = $assiduiteData['roleAverages'][$roleKey] ?? ['avg_assiduite'=>0,'avg_disponibilite'=>0,'avg_combined'=>0,'count'=>0]; @endphp
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
              <div class="card dashboard-card p-3 h-100">
                <div class="d-flex align-items-center mb-3">
                  <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                       style="width:42px;height:42px;background:{{ $roleMeta['color'] }}20;">
                    <i class="fas {{ $roleMeta['icon'] }}" style="color:{{ $roleMeta['color'] }};"></i>
                  </div>
                  <div>
                    <h6 class="mb-0 fw-bold">{{ $roleMeta['label'] }}</h6>
                    <small class="text-muted">{{ $rd['count'] }} évaluation(s)</small>
                  </div>
                </div>

                <!-- Score Trio -->
                <div class="row text-center">
                  <div class="col-4 border-end">
                    <div class="fw-bold fs-5" style="color:{{ $roleMeta['color'] }}">{{ number_format($rd['avg_assiduite'], 2) }}</div>
                    <small class="text-muted d-block" style="font-size:.7rem;" data-en="Attendance" data-fr="Assiduité">Assiduité</small>
                  </div>
                  <div class="col-4 border-end">
                    <div class="fw-bold fs-5" style="color:{{ $roleMeta['color'] }}">{{ number_format($rd['avg_disponibilite'], 2) }}</div>
                    <small class="text-muted d-block" style="font-size:.7rem;" data-en="Availability" data-fr="Disponibilité">Disponibilité</small>
                  </div>
                  <div class="col-4">
                    <div class="fw-bold fs-5" style="color:{{ $roleMeta['color'] }}">{{ number_format($rd['avg_combined'], 2) }}</div>
                    <small class="text-muted d-block" style="font-size:.7rem;" data-en="Combined" data-fr="Combiné">Combiné</small>
                  </div>
                </div>

                <!-- Mini progress bars -->
                <div class="mt-3">
                  <div class="d-flex justify-content-between mb-1" style="font-size:.75rem;">
                    <span class="text-muted">Assiduité</span>
                    <span>{{ number_format(($rd['avg_assiduite']/4)*100, 0) }}%</span>
                  </div>
                  <div class="progress mb-2" style="height:6px;">
                    <div class="progress-bar" style="width:{{ ($rd['avg_assiduite']/4)*100 }}%;background:{{ $roleMeta['color'] }};border-radius:4px;"></div>
                  </div>
                  <div class="d-flex justify-content-between mb-1" style="font-size:.75rem;">
                    <span class="text-muted">Disponibilité</span>
                    <span>{{ number_format(($rd['avg_disponibilite']/4)*100, 0) }}%</span>
                  </div>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar" style="width:{{ ($rd['avg_disponibilite']/4)*100 }}%;background:{{ $roleMeta['color'] }};opacity:.7;border-radius:4px;"></div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- ── Chart + Distribution Row ── -->
        <div class="row g-3 mb-4">
          <!-- Grouped Bar Chart -->
          <div class="col-md-7" data-aos="fade-right">
            <div class="card dashboard-card p-4 h-100">
              <h6 class="fw-bold mb-3">
                <i class="fas fa-chart-bar me-2 text-primary"></i>
                <span data-en="Score Comparison by Level" data-fr="Comparaison des Scores par Niveau">Score Comparison by Level</span>
              </h6>
              <canvas id="assiduiteBarChart" style="max-width:100%;max-height:260px;"></canvas>
            </div>
          </div>

          <!-- Rating Distribution Table -->
          <div class="col-md-5" data-aos="fade-left">
            <div class="card dashboard-card p-4 h-100">
              <h6 class="fw-bold mb-3">
                <i class="fas fa-poll me-2 text-primary"></i>
                <span data-en="Attendance Rating Distribution" data-fr="Distribution des Notes d'Assiduité">Attendance Rating Distribution</span>
              </h6>
              <div class="table-responsive">
                <table class="table table-sm" style="font-size:.82rem;">
                  <thead>
                    <tr>
                      <th>Note</th>
                      <th class="text-center" style="color:#00bfa5">N0</th>
                      <th class="text-center" style="color:#667eea">N+1</th>
                      <th class="text-center" style="color:#e53935">N+2</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($assiduiteData['distribution'] ?? [] as $score => $dist)
                      <tr>
                        <td>
                          <span class="badge bg-{{ $ratingLabels[$score]['class'] ?? 'secondary' }} me-1">{{ $score }}</span>
                          <span class="text-muted" style="font-size:.75rem;">{{ $ratingLabels[$score]['label'] ?? '' }}</span>
                        </td>
                        <td class="text-center fw-bold" style="color:#00bfa5">{{ $dist['n0'] }}</td>
                        <td class="text-center fw-bold" style="color:#667eea">{{ $dist['n1'] }}</td>
                        <td class="text-center fw-bold" style="color:#e53935">{{ $dist['n2'] }}</td>
                      </tr>
                    @endforeach
                    @if(empty($assiduiteData['distribution']))
                      <tr><td colspan="4" class="text-center text-muted">Aucune donnée disponible</td></tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- ── Per-Employee Detail Table (collapsible, filterable, paginated) ── -->
        <div id="assiduiteDetailTable" style="display:none;" data-aos="fade-up">
          <div class="card dashboard-card p-4">

            <!-- Table Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
              <h6 class="fw-bold mb-0">
                <i class="fas fa-users me-2 text-primary"></i>
                <span data-en="Per-Employee Attendance &amp; Availability Detail" data-fr="Détail par Collaborateur">Per-Employee Attendance &amp; Availability Detail</span>
                <span class="badge bg-primary ms-2" id="empVisibleCount">—</span>
                <span class="text-muted fw-normal" style="font-size:.8rem;"> / <span id="empTotalCount">—</span></span>
              </h6>
              <!-- Search + Dept Filter -->
              <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="width:220px;">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                  <input type="text" id="empSearch" class="form-control" placeholder="Search name…" oninput="filterEmpTable()">
                </div>
                <select id="empDeptFilter" class="form-select form-select-sm" style="width:160px;" onchange="filterEmpTable()">
                  <option value="">All Departments</option>
                  @foreach(($assiduiteData['employees'] ?? collect())->pluck('departement')->unique()->filter()->sort() as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                  @endforeach
                </select>
                <button class="btn btn-outline-secondary btn-sm" onclick="resetEmpFilters()" title="Clear filters">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-hover" id="empDetailTable" style="font-size:.82rem;">
                <thead class="table-dark">
                  <tr>
                    <th rowspan="2" class="align-middle">#</th>
                    <th rowspan="2" class="align-middle">Collaborateur</th>
                    <th rowspan="2" class="align-middle">Département</th>
                    <th colspan="3" class="text-center border-start" style="color:#00bfa5;">N0 (Auto)</th>
                    <th colspan="3" class="text-center border-start" style="color:#a5b4fc;">N+1</th>
                    <th colspan="3" class="text-center border-start" style="color:#fca5a5;">N+2</th>
                  </tr>
                  <tr>
                    <th class="text-center border-start" style="font-size:.75rem;">Assid.</th>
                    <th class="text-center" style="font-size:.75rem;">Dispo.</th>
                    <th class="text-center" style="font-size:.75rem;">Moy.</th>
                    <th class="text-center border-start" style="font-size:.75rem;">Assid.</th>
                    <th class="text-center" style="font-size:.75rem;">Dispo.</th>
                    <th class="text-center" style="font-size:.75rem;">Moy.</th>
                    <th class="text-center border-start" style="font-size:.75rem;">Assid.</th>
                    <th class="text-center" style="font-size:.75rem;">Dispo.</th>
                    <th class="text-center" style="font-size:.75rem;">Moy.</th>
                  </tr>
                </thead>
                <tbody id="empTableBody">
                  @forelse($assiduiteData['employees'] ?? [] as $i => $emp)
                    @php
                      $getBadge = function($v) {
                        if (is_null($v)) return '<span class="text-muted">—</span>';
                        $colors = [1=>'danger',2=>'warning',3=>'primary',4=>'success'];
                        return '<span class="badge bg-'.($colors[$v] ?? 'secondary').'">'.$v.'/4</span>';
                      };
                    @endphp
                    <tr class="emp-row"
                        data-name="{{ strtolower($emp->name) }}"
                        data-dept="{{ $emp->departement ?: '' }}">
                      <td class="text-muted" style="font-size:.75rem;">{{ $i + 1 }}</td>
                      <td><strong>{{ $emp->name }}</strong></td>
                      <td><span class="badge bg-secondary">{{ $emp->departement ?: 'N/A' }}</span></td>
                      <td class="text-center border-start">{!! $getBadge($emp->n0_assiduite) !!}</td>
                      <td class="text-center">{!! $getBadge($emp->n0_disponibilite) !!}</td>
                      <td class="text-center"><strong>{{ is_null($emp->n0_avg) ? '—' : number_format($emp->n0_avg,2) }}</strong></td>
                      <td class="text-center border-start">{!! $getBadge($emp->n1_assiduite) !!}</td>
                      <td class="text-center">{!! $getBadge($emp->n1_disponibilite) !!}</td>
                      <td class="text-center"><strong>{{ is_null($emp->n1_avg) ? '—' : number_format($emp->n1_avg,2) }}</strong></td>
                      <td class="text-center border-start">{!! $getBadge($emp->n2_assiduite) !!}</td>
                      <td class="text-center">{!! $getBadge($emp->n2_disponibilite) !!}</td>
                      <td class="text-center"><strong>{{ is_null($emp->n2_avg) ? '—' : number_format($emp->n2_avg,2) }}</strong></td>
                    </tr>
                  @empty
                    <tr id="empEmptyRow">
                      <td colspan="12" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Aucune évaluation d'assiduité trouvée
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Footer: no-results message + load-more controls -->
            <div id="empNoResults" class="text-center text-muted py-3" style="display:none;">
              <i class="fas fa-search fa-lg mb-1 d-block"></i>
              No employees match your filters.
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3" id="empPaginationBar">
              <small class="text-muted" id="empPaginationInfo"></small>
              <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" id="empLoadMoreBtn" onclick="loadMoreEmp()">
                  <i class="fas fa-chevron-down me-1"></i> Load more
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="empShowLessBtn" style="display:none;" onclick="showLessEmp()">
                  <i class="fas fa-chevron-up me-1"></i> Show less
                </button>
              </div>
            </div>

          </div>
        </div>

      </div><!-- end main section -->
    </div><!-- end main-content -->
  </div><!-- end main-wrapper -->

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- AOS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <!-- Sidebar JS (language / theme toggles) -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    // ── AOS Init ──────────────────────────────────────────────
    AOS.init({ duration: 800, offset: 80, once: true });

    // ── Assiduité Bar Chart ───────────────────────────────────
    (function () {
      const ctx = document.getElementById('assiduiteBarChart');
      if (!ctx) return;

      const n0 = {
        assiduite:     {{ $assiduiteData['roleAverages']['n0']['avg_assiduite']     ?? 0 }},
        disponibilite: {{ $assiduiteData['roleAverages']['n0']['avg_disponibilite'] ?? 0 }},
      };
      const n1 = {
        assiduite:     {{ $assiduiteData['roleAverages']['n1']['avg_assiduite']     ?? 0 }},
        disponibilite: {{ $assiduiteData['roleAverages']['n1']['avg_disponibilite'] ?? 0 }},
      };
      const n2 = {
        assiduite:     {{ $assiduiteData['roleAverages']['n2']['avg_assiduite']     ?? 0 }},
        disponibilite: {{ $assiduiteData['roleAverages']['n2']['avg_disponibilite'] ?? 0 }},
      };

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Assiduité', 'Disponibilité'],
          datasets: [
            {
              label: 'N0 (Auto)',
              data: [n0.assiduite, n0.disponibilite],
              backgroundColor: 'rgba(0,191,165,0.75)',
              borderColor: '#00bfa5',
              borderWidth: 1.5,
              borderRadius: 6,
            },
            {
              label: 'N+1',
              data: [n1.assiduite, n1.disponibilite],
              backgroundColor: 'rgba(102,126,234,0.75)',
              borderColor: '#667eea',
              borderWidth: 1.5,
              borderRadius: 6,
            },
            {
              label: 'N+2',
              data: [n2.assiduite, n2.disponibilite],
              backgroundColor: 'rgba(229,57,53,0.75)',
              borderColor: '#e53935',
              borderWidth: 1.5,
              borderRadius: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          scales: {
            y: {
              min: 0,
              max: 4,
              ticks: { stepSize: 1, font: { size: 11 } },
              grid: { color: '#f0f0f0' },
            },
            x: { grid: { display: false } },
          },
          plugins: {
            legend: { position: 'top', labels: { font: { size: 11 } } },
            tooltip: {
              callbacks: {
                label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toFixed(2)} / 4`,
              },
            },
          },
        },
      });
    })();

    // ── Toggle Detail Table ───────────────────────────────────
    function toggleAssiduiteTable() {
      const table = document.getElementById('assiduiteDetailTable');
      const btn   = document.getElementById('assiduiteToggleBtn');
      if (table.style.display === 'none') {
        table.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i><span data-en="Hide Details" data-fr="Masquer Détails">Hide Details</span>';
        initEmpTable();
      } else {
        table.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-table me-1"></i><span data-en="Show Details" data-fr="Voir Détails">Show Details</span>';
      }
    }

    // ── Employee Table: Filter + Pagination ───────────────────
    const EMP_PAGE_SIZE = 10;
    let empFiltered   = [];   // rows currently matching filters
    let empShownCount = 0;    // how many are visible right now
    let empTableReady = false;

    function initEmpTable() {
      if (empTableReady) return;
      empTableReady = true;
      filterEmpTable();
    }

    function filterEmpTable() {
      const search = (document.getElementById('empSearch').value || '').toLowerCase().trim();
      const dept   = (document.getElementById('empDeptFilter').value || '').toLowerCase();

      const allRows = Array.from(document.querySelectorAll('#empTableBody .emp-row'));

      // Hide all first
      allRows.forEach(r => r.style.display = 'none');

      // Apply filters
      empFiltered = allRows.filter(row => {
        const nameOk = !search || row.dataset.name.includes(search);
        const deptOk = !dept   || row.dataset.dept.toLowerCase() === dept;
        return nameOk && deptOk;
      });

      // Show first page
      empShownCount = 0;
      showNextPage();

      // No-results message
      document.getElementById('empNoResults').style.display =
        empFiltered.length === 0 ? 'block' : 'none';

      updateEmpCounts();
    }

    function showNextPage() {
      const end = Math.min(empShownCount + EMP_PAGE_SIZE, empFiltered.length);
      for (let i = empShownCount; i < end; i++) {
        empFiltered[i].style.display = '';
      }
      empShownCount = end;
      updateEmpControls();
    }

    function loadMoreEmp()  { showNextPage(); }

    function showLessEmp() {
      // Collapse back to first page
      empFiltered.forEach((r, i) => { r.style.display = i < EMP_PAGE_SIZE ? '' : 'none'; });
      empShownCount = Math.min(EMP_PAGE_SIZE, empFiltered.length);
      updateEmpControls();
      document.getElementById('assiduiteDetailTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function updateEmpControls() {
      const loadBtn = document.getElementById('empLoadMoreBtn');
      const lessBtn = document.getElementById('empShowLessBtn');
      const hasMore = empShownCount < empFiltered.length;
      const canLess = empShownCount > EMP_PAGE_SIZE;
      loadBtn.style.display = hasMore ? '' : 'none';
      lessBtn.style.display = canLess ? '' : 'none';
      document.getElementById('empPaginationBar').style.display =
        (hasMore || canLess) ? 'flex' : 'flex'; // always show info
      updateEmpCounts();
    }

    function updateEmpCounts() {
      document.getElementById('empVisibleCount').textContent = empShownCount;
      document.getElementById('empTotalCount').textContent   = empFiltered.length;
      document.getElementById('empPaginationInfo').textContent =
        `Showing ${empShownCount} of ${empFiltered.length} employee(s)`;
    }

    function resetEmpFilters() {
      document.getElementById('empSearch').value     = '';
      document.getElementById('empDeptFilter').value = '';
      filterEmpTable();
    }
  </script>

</body>
</html>
