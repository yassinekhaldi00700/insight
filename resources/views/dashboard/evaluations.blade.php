<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evaluations - UEMF Dashboard</title>

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
    <main class="main-content">
      <div class="content-wrapper">
        <header class="header">
          <h1>{{ __('Evaluations') }}</h1>
          <div class="header-actions">
            <span class="date">{{ date('Y-m-d') }}</span>
          </div>
        </header>

        <!-- Role Averages Chart Section -->
        <div class="card">
          <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> {{ __('Average Performance by Role') }}</h3>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="roleAverageChart"></canvas>
            </div>
          </div>
        </div>

        <!-- User Evaluation Scores Table -->
        <div class="card mt-4">
          <div class="card-header">
            <h3><i class="fas fa-users"></i> {{ __('Individual User Evaluation Scores') }}</h3>
          </div>
          <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
              <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by name or email...">
              </div>
              <div class="col-md-3">
                <select class="form-select" id="departmentFilter">
                  <option value="">All Departments</option>
                  @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <select class="form-select" id="scoreFilter">
                  <option value="">All Scores</option>
                  <option value="high">High Performers (≥3.5)</option>
                  <option value="medium">Medium Performers (2.5-3.4)</option>
                  <option value="low">Low Performers (<2.5)</option>
                </select>
              </div>
              <div class="col-md-2">
                <button class="btn btn-secondary" onclick="clearFilters()">
                  <i class="fas fa-times"></i> Clear
                </button>
              </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="evaluationsTable" data-paginate="10">
                <thead class="table-dark">
                  <tr>
                    <th onclick="sortTable(0)" style="cursor: pointer;">
                      Name <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortTable(1)" style="cursor: pointer;">
                      Email <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortTable(2)" style="cursor: pointer;">
                      Department <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortTable(3)" style="cursor: pointer;">
                      N0 Score <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortTable(4)" style="cursor: pointer;">
                      N1 Score <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortTable(5)" style="cursor: pointer;">
                      N2 Score <i class="fas fa-sort"></i>
                    </th>
                    <th>Average</th>
                  </tr>
                </thead>
                <tbody id="evaluationsTableBody">
                  @forelse($userEvaluations as $evaluation)
                    <tr>
                      <td>{{ $evaluation->name ?? 'N/A' }}</td>
                      <td>{{ $evaluation->email ?? 'N/A' }}</td>
                      <td>{{ $evaluation->departement ?? 'N/A' }}</td>
                      <td>
                        @if($evaluation->n0)
                          <span class="badge" style="background-color: {{ $evaluation->n0 >= 3.5 ? 'rgba(102, 126, 234, 1)' : ($evaluation->n0 >= 2.5 ? 'rgba(67, 160, 71, 1)' : 'rgba(229, 57, 53, 1)') }}; color: white;">
                            {{ number_format($evaluation->n0, 2) }}
                          </span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        @if($evaluation->n1)
                          <span class="badge" style="background-color: {{ $evaluation->n1 >= 3.5 ? 'rgba(102, 126, 234, 1)' : ($evaluation->n1 >= 2.5 ? 'rgba(67, 160, 71, 1)' : 'rgba(229, 57, 53, 1)') }}; color: white;">
                            {{ number_format($evaluation->n1, 2) }}
                          </span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        @if($evaluation->n2)
                          <span class="badge" style="background-color: {{ $evaluation->n2 >= 3.5 ? 'rgba(102, 126, 234, 1)' : ($evaluation->n2 >= 2.5 ? 'rgba(67, 160, 71, 1)' : 'rgba(229, 57, 53, 1)') }}; color: white;">
                            {{ number_format($evaluation->n2, 2) }}
                          </span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        @php
                          $scores = array_filter([$evaluation->n0, $evaluation->n1, $evaluation->n2]);
                          $average = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
                        @endphp
                        @if($average > 0)
                          <span class="badge" style="background-color: {{ $average >= 3.5 ? 'rgba(102, 126, 234, 1)' : ($average >= 2.5 ? 'rgba(67, 160, 71, 1)' : 'rgba(229, 57, 53, 1)') }}; color: white;">
                            {{ number_format($average, 2) }}
                          </span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>No evaluation data available</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Table Info -->
            <div class="d-flex justify-content-between align-items-center mt-3">
              <small class="text-muted">
                Showing <span id="showingCount">{{ count($userEvaluations) }}</span> of <span id="totalCount">{{ count($userEvaluations) }}</span> evaluations
              </small>
              <div>
                <button class="btn btn-success btn-sm" onclick="exportToCSV()">
                  <i class="fas fa-download"></i> Export CSV
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- ✅ Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ AOS Animation -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <!-- ✅ Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- ✅ Sidebar JS -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });

    function createRoleAverageChart() {
        const ctx = document.getElementById('roleAverageChart').getContext('2d');
        
        // Get data from PHP
        const roleData = @json($roleAverages ?? ['avg_n0' => 0, 'avg_n1' => 0, 'avg_n2' => 0]);
        
        if (!roleData || (roleData.avg_n0 == 0 && roleData.avg_n1 == 0 && roleData.avg_n2 == 0)) {
            const noDataDiv = document.createElement('div');
            noDataDiv.className = 'text-center py-4 text-muted';
            noDataDiv.innerHTML = '<i class="fas fa-chart-bar fa-3x mb-3"></i><p>No evaluation data available</p>';
            document.getElementById('roleAverageChart').parentNode.appendChild(noDataDiv);
            document.getElementById('roleAverageChart').style.display = 'none';
            return;
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['N0 (Self)', 'N1 (Supervisor)', 'N2 (Manager)'],
                datasets: [{
                    label: 'Average Score',
                    data: [
                        roleData.avg_n0 || 0,
                        roleData.avg_n1 || 0,
                        roleData.avg_n2 || 0
                    ],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(67, 160, 71, 0.8)',
                        'rgba(229, 57, 53, 0.8)'
                    ],
                    borderColor: [
                        'rgba(102, 126, 234, 1)',
                        'rgba(67, 160, 71, 1)',
                        'rgba(229, 57, 53, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins',
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y.toFixed(2)}/4`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4,
                        ticks: {
                            stepSize: 0.5,
                            font: {
                                family: 'Poppins',
                                size: 11
                            },
                            callback: function(value) {
                                return value.toFixed(1);
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        title: {
                            display: true,
                            text: 'Score (Max: 4.0)',
                            font: {
                                family: 'Poppins',
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Poppins',
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutBounce'
                }
            }
        });
    }

    // Initialize chart when page loads
    document.addEventListener('DOMContentLoaded', function() {
        createRoleAverageChart();
        initializeTableFilters();
    });

    // Table filtering and sorting functionality
    function initializeTableFilters() {
        const searchInput = document.getElementById('searchInput');
        const departmentFilter = document.getElementById('departmentFilter');
        const scoreFilter = document.getElementById('scoreFilter');

        // Add event listeners for filters
        searchInput.addEventListener('input', filterTable);
        departmentFilter.addEventListener('change', filterTable);
        scoreFilter.addEventListener('change', filterTable);
    }

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const departmentFilter = document.getElementById('departmentFilter').value;
        const scoreFilter = document.getElementById('scoreFilter').value;
        const tbody = document.getElementById('evaluationsTableBody');
        const rows = tbody.getElementsByTagName('tr');
        let visibleCount = 0;

        for (let row of rows) {
            if (row.cells.length < 7) continue; // Skip "no data" row

            const name = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const department = row.cells[2].textContent;
            const averageCell = row.cells[6];
            
            // Get average score
            let averageScore = 0;
            const averageBadge = averageCell.querySelector('.badge');
            if (averageBadge) {
                averageScore = parseFloat(averageBadge.textContent);
            }

            let showRow = true;

            // Search filter
            if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) {
                showRow = false;
            }

            // Department filter
            if (departmentFilter && department !== departmentFilter) {
                showRow = false;
            }

            // Score filter
            if (scoreFilter) {
                if (scoreFilter === 'high' && averageScore < 3.5) {
                    showRow = false;
                } else if (scoreFilter === 'medium' && (averageScore < 2.5 || averageScore >= 3.5)) {
                    showRow = false;
                } else if (scoreFilter === 'low' && averageScore >= 2.5) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        }

        // Update showing count
        document.getElementById('showingCount').textContent = visibleCount;
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('scoreFilter').value = '';
        filterTable();
    }

    // Table sorting
    let sortDirection = {};
    function sortTable(columnIndex) {
        const table = document.getElementById('evaluationsTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.cells.length >= 7);
        
        // Toggle sort direction
        sortDirection[columnIndex] = !sortDirection[columnIndex];
        const ascending = sortDirection[columnIndex];

        rows.sort((a, b) => {
            let aValue = a.cells[columnIndex].textContent.trim();
            let bValue = b.cells[columnIndex].textContent.trim();

            // Handle numeric columns (scores)
            if (columnIndex >= 3 && columnIndex <= 6) {
                const aBadge = a.cells[columnIndex].querySelector('.badge');
                const bBadge = b.cells[columnIndex].querySelector('.badge');
                aValue = aBadge ? parseFloat(aBadge.textContent) : 0;
                bValue = bBadge ? parseFloat(bBadge.textContent) : 0;
                return ascending ? aValue - bValue : bValue - aValue;
            }

            // Handle text columns
            if (aValue === 'N/A') aValue = '';
            if (bValue === 'N/A') bValue = '';
            
            return ascending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        // Update sort icon
        document.querySelectorAll('th i.fas').forEach(icon => {
            icon.className = 'fas fa-sort';
        });
        const currentIcon = document.querySelectorAll('th')[columnIndex].querySelector('i');
        currentIcon.className = ascending ? 'fas fa-sort-up' : 'fas fa-sort-down';
    }

    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById('evaluationsTable');
        const rows = table.querySelectorAll('tr');
        let csvContent = '';

        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            const rowData = [];
            
            cells.forEach(cell => {
                // Get text content, handling badges
                let text = cell.textContent.trim();
                if (cell.querySelector('.badge')) {
                    text = cell.querySelector('.badge').textContent.trim();
                }
                // Escape quotes and wrap in quotes if contains comma
                if (text.includes(',') || text.includes('"')) {
                    text = '"' + text.replace(/"/g, '""') + '"';
                }
                rowData.push(text);
            });
            
            csvContent += rowData.join(',') + '\n';
        });

        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'user_evaluations_' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
  </script>

</body>
</html>
