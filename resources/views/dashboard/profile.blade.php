<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - UEMF Dashboard</title>

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
          <h1>{{ __('Profile Settings') }}</h1>
          <div class="header-actions">
            <span class="date">{{ date('Y-m-d') }}</span>
          </div>
        </header>

        <!-- Success/Error Messages -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <div class="row">
          <!-- Profile Overview Card -->
          <div class="col-md-4">
            <div class="card" data-aos="fade-up">
              <div class="card-body text-center">
                <div class="profile-avatar mb-3">
                  @if($admin->avatar)
                    <img src="{{ asset('storage/avatars/' . $admin->avatar) }}" alt="Profile Picture" class="rounded-circle" width="120" height="120">
                  @else
                    <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: #f8f9fa; border: 3px solid #dee2e6;">
                      <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                  @endif
                </div>
                <h4 class="mb-1">{{ $admin->name }}</h4>
                <p class="text-muted mb-2">{{ $admin->role }}</p>
                <p class="text-muted small">{{ $admin->department }}</p>
                
                <div class="mt-3">
                  <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Member since {{ date('F Y', strtotime($admin->created_at)) }}
                  </small>
                </div>
              </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4" data-aos="fade-up" data-aos-delay="100">
              <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Admin Statistics</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-6">
                    <div class="stat-item">
                      <h4 class="text-primary">{{ DB::table('users')->count() }}</h4>
                      <small class="text-muted">Total Users</small>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="stat-item">
                      <h4 class="text-success">{{ DB::table('users')->whereNotNull('departement')->distinct('departement')->count() }}</h4>
                      <small class="text-muted">Departments</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Edit Form -->
          <div class="col-md-8">
            <div class="card" data-aos="fade-up" data-aos-delay="200">
              <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
              </div>
              <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  
                  <!-- Personal Information -->
                  <div class="row mb-4">
                    <div class="col-12">
                      <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i>Personal Information
                      </h6>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                      <label for="name" class="form-label">Full Name</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                      <label for="phone" class="form-label">Phone Number</label>
                      <input type="text" class="form-control" id="phone" name="phone" value="{{ $admin->phone }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                      <label for="department" class="form-label">Department</label>
                      <input type="text" class="form-control" id="department" name="department" value="{{ $admin->department }}">
                    </div>
                  </div>

                  <!-- Avatar Upload -->
                  <div class="row mb-4">
                    <div class="col-12">
                      <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-camera me-2"></i>Profile Picture
                      </h6>
                    </div>
                    
                    <div class="col-12 mb-3">
                      <label for="avatar" class="form-label">Upload New Avatar</label>
                      <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                      <small class="text-muted">Supported formats: JPEG, PNG, JPG. Max size: 2MB</small>
                    </div>
                  </div>

                  <!-- Password Change -->
                  <div class="row mb-4">
                    <div class="col-12">
                      <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-lock me-2"></i>Change Password
                      </h6>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                      <label for="current_password" class="form-label">Current Password</label>
                      <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                      <label for="new_password" class="form-label">New Password</label>
                      <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                      <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                    </div>
                  </div>

                  <!-- Form Actions -->
                  <div class="row">
                    <div class="col-12">
                      <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                          <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <button type="reset" class="btn btn-secondary">
                          <i class="fas fa-undo me-2"></i>Reset Form
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                          <i class="fas fa-times me-2"></i>Cancel
                        </a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Account Settings -->
            <div class="card mt-4" data-aos="fade-up" data-aos-delay="300">
              <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Account Settings</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                      <label class="form-check-label" for="emailNotifications">
                        Email Notifications
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                      <label class="form-check-label" for="twoFactorAuth">
                        Two-Factor Authentication
                      </label>
                    </div>
                  </div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-2"></i>Download Account Data
                  </button>
                  <button class="btn btn-outline-danger btn-sm ms-2">
                    <i class="fas fa-trash me-2"></i>Delete Account
                  </button>
                </div>
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

  <!-- ✅ Sidebar JS -->
  <script src="{{ asset('js/sidebar.js') }}"></script>

  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });

    // Avatar preview
    document.getElementById('avatar').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const avatarContainer = document.querySelector('.profile-avatar');
          avatarContainer.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="rounded-circle" width="120" height="120">`;
        };
        reader.readAsDataURL(file);
      }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('new_password_confirmation').value;
      
      if (newPassword && newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New passwords do not match!');
        return false;
      }
    });
  </script>

</body>
</html>
