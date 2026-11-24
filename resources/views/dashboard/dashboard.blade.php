<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evaluation Dashboard</title>

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
        <div class="dashboard-header text-center mb-4 ">
            <div class="row align-items-center">
            <div class="col-12 col-md-6 text-md-start"> 
              <h2 data-en="Dashboard" data-fr="Tableau de Bord">Dashboard</h2>
              <p class="text-muted" data-en="Application of Evaluation" data-fr="Application d'Évaluation">Application of Evaluation</p>
            </div>
                 <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0"><div class="btn-group flex-wrap gap-2" role="group">
            <a href="{{ route('dashboard.export.incomplete.n0') }}" 
               class="button_import" 
               title="Export to Excel">
              <i class="fas fa-file-excel me-1"></i>
              <span data-en="Export Excel" data-fr="Exporter Excel">Export Excel</span>
              <img src="{{ asset('dash/import.svg') }}" alt="" aria-hidden="true">
            </a>
            <button type="button" 
                    class="button_import" 
                    onclick="showIncompleteN0Overview()"
                    id="overviewBtn"
                    title="View Incomplete N0 Overview">
              <i class="fas fa-eye me-1"></i>
              <span data-en="Overview" data-fr="Aperçu">Overview</span>
            </button>
            <!-- <button type="button" 
                    class="btn btn-primary btn-sm" 
                    onclick="sendReminderEmails()"
                    id="sendRemindersBtn"
                    title="Send reminder emails">
              <i class="fas fa-envelope me-1"></i>
              Send Reminders
            </button> -->
            
    </div></div></div>
        </div>
         
          

    <div class="row g-4 justify-content-center">
      <div class="col-md-6 col-lg-3" data-aos="fade-up">
        <div class="card dashboard-card text-center p-3">
          <h5 class="card-title" data-en="Employees" data-fr="Collaborateurs">Employees</h5>
          <div class="dashboard-number">{{ $stats['totalUsers'] ?? 0 }}</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="card dashboard-card text-center p-3">
          <h5 class="card-title" data-en="Employees Completed" data-fr="Collaborateurs Terminés">Employees Completed</h5>
          <canvas id="collabDone" role="img" aria-label="Employees completed: {{ $stats['n0CompletedPercent'] ?? 0 }}%"></canvas>
        </div>
      </div>

      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="card dashboard-card text-center p-3">
          <h5 class="card-title">N+1</h5>
          <canvas id="n1" role="img" aria-label="N+1 completed: {{ $stats['n1CompletedPercent'] ?? 0 }}%"></canvas>
        </div>
      </div>

      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="card dashboard-card text-center p-3">
          <h5 class="card-title">N+2</h5>
          <canvas id="n2" role="img" aria-label="N+2 completed: {{ $stats['n2CompletedPercent'] ?? 0 }}%"></canvas>
        </div>
      </div>
    </div>

    <!-- Bar Chart Section -->
    
 <!-- Incomplete N0 Evaluations Section - Hidden, will show in modal -->
 <!-- <div class="row mt-4" style="display: none;">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Incomplete N0 Evaluations
          </h5>
        </div>
        <div class="card-body">
          Content moved to modal
        </div>
      </div>
    </div>
  </div> -->
    <!-- Final Results Section -->
    <div class="row g-4 mt-1">
      <!-- Average Final Result -->
      <div class="col-12 col-lg-6" data-aos="fade-up" data-aos-delay="500">
        <div class="card dashboard-card p-4 h-100">
          <h5 class="card-title text-center mb-4">
            <i class="fas fa-chart-line"></i> 
            <span data-en="Overall Average Score" data-fr="Score Moyen Global">Overall Average Score</span>
          </h5>
          <div class="text-center">
            <div class="display-4 fw-bold text-primary mb-2">
              {{ number_format($finalResultsData['average'] ?? 0, 2) }}
            </div>
            <p class="text-muted mb-0" data-en="Average Final Result" data-fr="Résultat Final Moyen">Average Final Result</p>
            <small class="text-muted" data-en="Based on all completed evaluations" data-fr="Basé sur toutes les évaluations terminées">Based on all completed evaluations</small>
          </div>
          
          <!-- Action Buttons -->
          <div class="mt-4 d-flex flex-column gap-2">
            <button type="button" 
                    class="btn btn-success btn-sm" 
                    onclick="downloadAllResults()"
                    id="downloadAllBtn"
                    title="Download All User Results">
              <i class="fas fa-download me-2"></i>
              <span data-en="Download All Results" data-fr="Télécharger Tous les Résultats">Download All Results</span>
            </button>
            <button type="button" 
                    class="btn btn-primary1 btn-sm" 
                    onclick="showAllResultsOverview()"
                    id="overviewAllBtn"
                    title="View All Results Overview">
              <i class="fas fa-chart-bar me-2"></i>
              <span data-en="Results Overview" data-fr="Aperçu des Résultats">Results Overview</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Top 5 Performers -->
      <div class="col-12 col-lg-6" data-aos="fade-up" data-aos-delay="600">
        <div class="card dashboard-card p-4 h-100">
          <h5 class="card-title text-center mb-4">
            <i class="fas fa-trophy"></i> 
            <span data-en="Top 5 Performers" data-fr="Top 5 Performeurs">Top 5 Performers</span>
          </h5>
          <div class="top-performers-list">
            @forelse($finalResultsData['topPerformers'] ?? [] as $index => $performer)
            <div class="performer-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
              <div class="performer-info d-flex align-items-center">
                <div class="rank-badge me-3">
                  @if($index === 0)
                  <img src="/dash/trophy.svg" alt="1st Place" style="width: 32px; height: 32px;">
                    <img src="/dash/1st.svg" alt="1st Place" style="width: 32px; height: 32px;">
                    
                  @elseif($index === 1)
                    <img src="/dash/2nd.svg" alt="2nd Place" style="width: 32px; height: 32px;">
                  @elseif($index === 2)
                    <img src="/dash/3rd.svg" alt="3rd Place" style="width: 32px; height: 32px;">
                  @else
                    <span class="badge bg-primary1 rounded-pill">#{{ $index + 1 }}</span>
                  @endif
                </div>
                <div>
                  <h6 class="mb-1 fw-bold">{{ $performer->name ?? 'N/A' }}</h6>
                  <small class="text-muted">Employee</small>
                </div>
              </div>
              <div class="performer-score">
                <span class="h5 fw-bold text-success1">
                  {{ number_format($performer->final_result ?? 0, 2) }}
                </span>
              </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">
              <i class="fas fa-users fa-3x mb-3"></i>
              <p>No performance data available</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
    </div>
    
  </div>



  <!-- Incomplete N0 Overview Modal -->
  <div class="modal fade" id="incompleteN0Modal" tabindex="-1" aria-labelledby="incompleteN0ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="incompleteN0ModalLabel">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Incomplete N0 Evaluations Overview
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="btn-group" role="group">
              <a href="{{ route('dashboard.export.incomplete.n0') }}" 
                 class="btn btn-success btn-sm" 
                 title="Export to Excel">
                <i class="fas fa-file-excel me-1"></i>
                Export Excel
              </a>
              <button type="button" 
                      class="btn btn-primary btn-sm ms-2" 
                      onclick="sendReminderEmails()"
                      id="sendRemindersBtn"
                      title="Send reminder emails">
                <i class="fas fa-envelope me-1"></i>
                Send Reminders
              </button>
            </div>
          </div>

          @if(isset($incompleteN0Users) && count($incompleteN0Users) > 0)
            <div class="alert alert-warning" role="alert">
              <i class="fas fa-info-circle me-2"></i>
              <strong>{{ count($incompleteN0Users) }}</strong> users have not completed their N0 evaluations.
            </div>
            
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($incompleteN0Users as $user)
                    <tr>
                      <td>{{ $user->id }}</td>
                      <td>
                        <i class="fas fa-user-clock text-warning me-2"></i>
                        {{ $user->name }}
                      </td>
                      <td>
                        <span class="badge bg-secondary">{{ $user->departement ?: 'N/A' }}</span>
                      </td>
                      <td>
                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                          <i class="fas fa-envelope me-1"></i>
                          {{ $user->email }}
                        </a>
                      </td>
                      <td>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm"
                                onclick="sendIndividualReminder({{ $user->id }})"
                                title="Send individual reminder">
                          <i class="fas fa-paper-plane"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle me-2"></i>
              Great! All N0 users have completed their evaluations.
            </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- All Results Overview Modal -->
  <div class="modal fade" id="allResultsModal" tabindex="-1" aria-labelledby="allResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden;">

        <div class="modal-header border-0 pb-0 px-4 pt-4">
          <div>
            <h5 class="modal-title fw-bold mb-1" id="allResultsModalLabel">
              Complete Evaluation Results
            </h5>
            <p class="text-muted mb-0" style="font-size:.85rem;">Comprehensive overview of all user evaluations and final scores</p>
          </div>
          <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="{{ route('dashboard.export.all.results') }}" class="btn btn-sm btn-outline-secondary" title="Download Excel">
              <i class="fas fa-download me-1"></i> Export
            </a>
            <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>

        <div class="modal-body px-4 pt-3 pb-4">

          <!-- Summary Strip -->
          <div class="row g-3 mb-4">
            @php
              $totalU   = $stats['totalUsers'] ?? 0;
              $totalEv  = count($finalResultsData['topPerformers'] ?? []);
              $avgSc    = number_format($finalResultsData['average'] ?? 0, 2);
              $topSc    = isset($finalResultsData['topPerformers'][0])
                            ? number_format($finalResultsData['topPerformers'][0]->final_result, 2)
                            : '—';
              $summaries = [
                ['icon'=>'fa-users',       'label'=>'Total Users',            'value'=>$totalU ],
                ['icon'=>'fa-check-circle','label'=>'With Final Score',        'value'=>$totalEv],
                ['icon'=>'fa-chart-line',  'label'=>'Average Score',           'value'=>$avgSc  ],
                ['icon'=>'fa-trophy',      'label'=>'Highest Score',           'value'=>$topSc  ],
              ];
            @endphp
            @foreach($summaries as $s)
              <div class="col-6 col-md-3">
                <div class="p-3 rounded-3" style="border:1px solid #e9ecef;">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas {{ $s['icon'] }} text-muted" style="font-size:.85rem;"></i>
                    <span class="text-muted" style="font-size:.78rem;">{{ $s['label'] }}</span>
                  </div>
                  <div class="fw-bold fs-5">{{ $s['value'] }}</div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Results Table -->
          @if(isset($finalResultsData['topPerformers']) && count($finalResultsData['topPerformers']) > 0)
            <div class="table-responsive">
              <table class="table table-sm align-middle" style="font-size:.875rem;">
                <thead style="border-bottom:2px solid #e9ecef;">
                  <tr class="text-muted" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">
                    <th class="fw-semibold pb-2">#</th>
                    <th class="fw-semibold pb-2">Name</th>
                    <th class="fw-semibold pb-2">Department</th>
                    <th class="fw-semibold pb-2 text-center">Score</th>
                    <th class="fw-semibold pb-2 text-center">Level</th>
                    <th class="fw-semibold pb-2"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($finalResultsData['topPerformers'] as $index => $performer)
                    @php
                      $score = $performer->final_result ?? 0;
                      if ($score >= 3.5)      { $levelLabel = 'Excellent'; }
                      elseif ($score >= 3.0)  { $levelLabel = 'Good'; }
                      elseif ($score >= 2.5)  { $levelLabel = 'Average'; }
                      else                    { $levelLabel = 'Needs Improvement'; }
                    @endphp
                    <tr style="border-bottom:1px solid #f3f4f6;">
                      <td class="text-muted" style="width:40px;">{{ $index + 1 }}</td>
                      <td><span class="fw-semibold">{{ $performer->name ?? 'N/A' }}</span></td>
                      <td class="text-muted">{{ $performer->departement ?? '—' }}</td>
                      <td class="text-center fw-semibold">{{ number_format($score, 2) }}</td>
                      <td class="text-center text-muted" style="font-size:.82rem;">{{ $levelLabel }}</td>
                      <td class="text-end">
                        <button type="button"
                                class="btn btn-sm"
                                style="background:none;border:1px solid #e5e7eb;border-radius:6px;padding:3px 10px;font-size:.78rem;color:#6b7280;"
                                onclick="viewUserDetails({{ $performer->id ?? 0 }})"
                                title="View Details">
                          Details
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5 text-muted">
              <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
              <p class="mb-0">No evaluation results found.</p>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>


</div>

  
  <!-- @include('footer') -->

  <!-- ✅ JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
  <!-- ✅ Centralized Sidebar JS -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    AOS.init();

    // sidebar.js applies the saved theme on DOMContentLoaded, after this script runs
    function isDarkTheme() {
      try { return localStorage.getItem('theme') === 'dark'; } catch (e) { return false; }
    }

    function createCircularChart(id, value, color) {
      const ctx = document.getElementById(id).getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [value, 100 - value],
            backgroundColor: [color, isDarkTheme() ? '#2a3441' : '#e9ecef'],
            borderWidth: 0,
            borderRadius: value > 0 && value < 100 ? 6 : 0
          }]
        },
        options: {
          cutout: '75%',
          plugins: {
            tooltip: { enabled: false },
            legend: { display: false }
          }
        },
        plugins: [{
          beforeDraw: function(chart) {
            const { width, height, ctx } = chart;
            ctx.restore();
            
            // Calculate font size based on canvas size
            const fontSize = Math.min(width, height) / 6;
            ctx.font = `600 ${fontSize}px Poppins, sans-serif`;
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";
            
            // Draw percentage text
            const text = value + "%";
            const textX = width / 2;
            const textY = height / 2;
            
            ctx.fillStyle = isDarkTheme() ? "#ffffff" : "#212529";
            ctx.fillText(text, textX, textY);
            ctx.save();
          }
        }]
      });
    }

    // ✅ Initialize all charts with dynamic values from server
    const n0Percent = {{ $stats['n0CompletedPercent'] ?? 0 }};
    const n1Percent = {{ $stats['n1CompletedPercent'] ?? 0 }};
    const n2Percent = {{ $stats['n2CompletedPercent'] ?? 0 }};

    createCircularChart("collabDone", n0Percent, "#00bfa5");
    createCircularChart("n1", n1Percent, "#667eea");
    createCircularChart("n2", n2Percent, "#e53935");

    // Function to show Incomplete N0 Overview Modal
    function showIncompleteN0Overview() {
      const incompleteN0Modal = new bootstrap.Modal(document.getElementById('incompleteN0Modal'));
      incompleteN0Modal.show();
    }

    // Function to send reminder emails
    function sendReminderEmails() {
      const btn = document.getElementById('sendRemindersBtn');
      const originalText = btn.innerHTML;
      
      // Show loading state
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
      btn.disabled = true;
      
      fetch('{{ route("dashboard.send.reminders") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          const alert = document.createElement('div');
          alert.className = 'alert alert-success alert-dismissible fade show mt-3';
          alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          
          const modalBody = document.querySelector('#incompleteN0Modal .modal-body');
          modalBody.insertBefore(alert, modalBody.firstChild);
          
          // Auto-dismiss after 5 seconds
          setTimeout(() => {
            if (alert.parentNode) {
              alert.remove();
            }
          }, 5000);
        } else {
          throw new Error(data.error || 'Failed to send emails');
        }
      })
      .catch(error => {
        // Show error message
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alert.innerHTML = `
          <i class="fas fa-exclamation-triangle me-2"></i>
          Error: ${error.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const modalBody = document.querySelector('#incompleteN0Modal .modal-body');
        modalBody.insertBefore(alert, modalBody.firstChild);
      })
      .finally(() => {
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
      });
    }
    
    // Function to send individual reminder
    function sendIndividualReminder(userId) {
      // This would be implemented similar to the above function
      // but for individual users
      console.log('Sending individual reminder to user:', userId);
      
      // Show a simple confirmation for now
      const confirmation = confirm(`Send reminder email to user ${userId}?`);
      if (confirmation) {
        alert('Individual reminder functionality would be implemented here');
      }
    }

    // ========================================
    // ALL RESULTS FUNCTIONALITY
    // ========================================
    
    // Function to show All Results Overview Modal
    function showAllResultsOverview() {
      const allResultsModal = new bootstrap.Modal(document.getElementById('allResultsModal'));
      allResultsModal.show();
    }

    // Function to download all results
    function downloadAllResults() {
      const btn = document.getElementById('downloadAllBtn');
      const originalText = btn.innerHTML;
      
      // Show loading state
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Downloading...';
      btn.disabled = true;
      
      // Create download URL - you'll need to create this route in your Laravel app
      const downloadUrl = '{{ route("dashboard.export.all.results") }}';
      
      try {
        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = `all_user_results_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show mt-3';
        alert.innerHTML = `
          <i class="fas fa-check-circle me-2"></i>
          Download started successfully! Check your downloads folder.
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Find modal body or dashboard container to show alert
        const targetContainer = document.querySelector('#allResultsModal .modal-body') || 
                               document.querySelector('.dashboard-container');
        if (targetContainer) {
          targetContainer.insertBefore(alert, targetContainer.firstChild);
          
          // Auto-dismiss after 5 seconds
          setTimeout(() => {
            if (alert.parentNode) {
              alert.remove();
            }
          }, 5000);
        }
        
      } catch (error) {
        console.error('Download error:', error);
        
        // Show error message
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alert.innerHTML = `
          <i class="fas fa-exclamation-triangle me-2"></i>
          Error starting download: ${error.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const targetContainer = document.querySelector('#allResultsModal .modal-body') || 
                               document.querySelector('.dashboard-container');
        if (targetContainer) {
          targetContainer.insertBefore(alert, targetContainer.firstChild);
        }
      } finally {
        // Reset button after 2 seconds
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.disabled = false;
        }, 2000);
      }
    }

    // Function to refresh results data
    function refreshResultsData() {
      // Show loading state on all relevant elements
      const refreshBtn = document.querySelector('#allResultsModal .btn-info');
      if (refreshBtn) {
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
        refreshBtn.disabled = true;
        
        // Simulate refresh (you can replace this with actual AJAX call)
        setTimeout(() => {
          refreshBtn.innerHTML = originalText;
          refreshBtn.disabled = false;
          
          // Show success message
          const alert = document.createElement('div');
          alert.className = 'alert alert-info alert-dismissible fade show mt-3';
          alert.innerHTML = `
            <i class="fas fa-sync me-2"></i>
            Data refreshed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          
          const modalBody = document.querySelector('#allResultsModal .modal-body');
          if (modalBody) {
            modalBody.insertBefore(alert, modalBody.firstChild);
            
            setTimeout(() => {
              if (alert.parentNode) {
                alert.remove();
              }
            }, 3000);
          }
        }, 1500);
      }
    }

    // Function to view user details
    function viewUserDetails(userId) {
      if (!userId) {
        alert('User ID not found');
        return;
      }
      
      // You can implement this to show detailed user evaluation
      const confirmation = confirm(`View detailed evaluation for user ID: ${userId}?`);
      if (confirmation) {
        // Here you could open another modal or redirect to user detail page
        console.log('Viewing details for user:', userId);
        alert('User details functionality would be implemented here');
      }
    }

    // ========================================
    // LANGUAGE SWITCHING FUNCTIONALITY
    // ========================================
    
    // Language translations object
    const translations = {
      en: {
        // Sidebar
        'UEMF Dashboard': 'UEMF Dashboard',
        'Talent Management': 'Talent Management',
        'Dashboard': 'Dashboard',
        'Employees': 'Employees',
        'Analytics': 'Analytics',
        'Evaluations': 'Evaluations',
        'Settings': 'Settings',
        'Profile': 'Profile',
        'Logout': 'Logout',
        
        // Main Content
        'Application of Evaluation': 'Application of Evaluation',
        'Export Excel': 'Export Excel',
        'Overview': 'Overview',
        
        // Dashboard Cards
        'Employees Completed': 'Employees Completed',
        
        // Results Section
        'Overall Average Score': 'Overall Average Score',
        'Average Final Result': 'Average Final Result',
        'Based on all completed evaluations': 'Based on all completed evaluations',
        'Download All Results': 'Download All Results',
        'Results Overview': 'Results Overview',
        'Top 5 Performers': 'Top 5 Performers',
        
        // Modal Content
        'All User Results Overview': 'All User Results Overview',
        'Complete Evaluation Results': 'Complete Evaluation Results',
        'Comprehensive overview of all user evaluations and final scores': 'Comprehensive overview of all user evaluations and final scores',
        'Download Excel': 'Download Excel',
        'Refresh': 'Refresh',
        'Total Users': 'Total Users',
        'Completed Evaluations': 'Completed Evaluations',
        'Average Score': 'Average Score',
        'Highest Score': 'Highest Score',
        'Close': 'Close'
      },
      fr: {
        // Sidebar
        'UEMF Dashboard': 'Tableau de Bord UEMF',
        'Talent Management': 'Gestion des Talents',
        'Dashboard': 'Tableau de Bord',
        'Employees': 'Collaborateurs',
        'Analytics': 'Analytiques',
        'Evaluations': 'Évaluations',
        'Settings': 'Paramètres',
        'Profile': 'Profil',
        'Logout': 'Déconnexion',
        
        // Main Content
        'Application of Evaluation': 'Application d\'Évaluation',
        'Export Excel': 'Exporter Excel',
        'Overview': 'Aperçu',
        
        // Dashboard Cards
        'Employees Completed': 'Collaborateurs Terminés',
        
        // Results Section
        'Overall Average Score': 'Score Moyen Global',
        'Average Final Result': 'Résultat Final Moyen',
        'Based on all completed evaluations': 'Basé sur toutes les évaluations terminées',
        'Download All Results': 'Télécharger Tous les Résultats',
        'Results Overview': 'Aperçu des Résultats',
        'Top 5 Performers': 'Top 5 Performeurs',
        
        // Modal Content
        'All User Results Overview': 'Aperçu de Tous les Résultats',
        'Complete Evaluation Results': 'Résultats d\'Évaluation Complets',
        'Comprehensive overview of all user evaluations and final scores': 'Aperçu complet de toutes les évaluations et scores finaux',
        'Download Excel': 'Télécharger Excel',
        'Refresh': 'Actualiser',
        'Total Users': 'Total Utilisateurs',
        'Completed Evaluations': 'Évaluations Terminées',
        'Average Score': 'Score Moyen',
        'Highest Score': 'Score Élevé',
        'Close': 'Fermer'
      }
    };

    // Initialize dashboard specific functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Sidebar functionality (language toggle, theme toggle) is handled by sidebar.js
      
      // Initialize AOS animations
      AOS.init({
        duration: 800,
        offset: 100,
        once: true
      });
    });
  </script>

</body>
</html>
