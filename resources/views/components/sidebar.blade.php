<!-- Mobile top bar (visible below 992px) -->
<div class="mobile-topbar">
  <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-controls="appSidebar" aria-expanded="false" aria-label="Open menu">
    <i class="fas fa-bars"></i>
  </button>
  <p class="mobile-topbar__title" data-en="UEMF Dashboard" data-fr="Tableau de Bord UEMF">UEMF Dashboard</p>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- Sidebar Component -->
<aside class="sidebar" id="appSidebar" aria-label="Main navigation">
  <div class="sidebar-header">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
      <a href="{{ route('dashboard') }}" class="sidebar-brand" aria-label="UEMF Insights">
        <img src="{{ asset('img/logo.png') }}" alt="UEMF Insights">
      </a>
      <div class="d-flex gap-2">
        <!-- Language Toggle -->
        <div class="language-toggle">
          <button type="button" id="languageToggle" class="language-toggle-btn" title="Switch Language" aria-label="Switch language">
            <span id="currentLang">EN</span>
            <i class="fas fa-globe ms-1"></i>
          </button>
        </div>
        <!-- Dark Mode Toggle -->
        <div class="theme-toggle">
          <button type="button" id="themeToggle" class="theme-toggle-btn" title="Toggle Theme" aria-label="Toggle dark mode">
            <i class="fas fa-sun" id="lightIcon"></i>
            <i class="fas fa-moon" id="darkIcon" style="display: none;"></i>
          </button>
        </div>
      </div>
    </div>
    <h4 data-en="UEMF Dashboard" data-fr="Tableau de Bord UEMF">UEMF Dashboard</h4>
    <p data-en="Talent Management" data-fr="Gestion des Talents">Talent Management</p>
  </div>

  <nav class="sidebar-menu">
    <div class="sidebar-label" data-en="Overview" data-fr="Vue d'ensemble">Overview</div>

    <a href="{{ route('dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
      <i class="fas fa-home"></i>
      <span data-en="Dashboard" data-fr="Tableau de Bord">Dashboard</span>
    </a>

    <a href="{{ route('admin.objectives') }}" class="sidebar-menu-item {{ request()->routeIs('admin.objectives') ? 'active' : '' }}" @if(request()->routeIs('admin.objectives')) aria-current="page" @endif>
      <i class="fas fa-bullseye"></i>
      <span data-en="Objectives" data-fr="Objectifs">Objectives</span>
    </a>

    <a href="{{ route('admin.evaluations') }}" class="sidebar-menu-item {{ request()->routeIs('admin.evaluations') ? 'active' : '' }}" @if(request()->routeIs('admin.evaluations')) aria-current="page" @endif>
      <i class="fas fa-clipboard-check"></i>
      <span data-en="Evaluations" data-fr="Évaluations">Evaluations</span>
    </a>

    <a href="{{ route('admin.assiduite') }}" class="sidebar-menu-item {{ request()->routeIs('admin.assiduite') ? 'active' : '' }}" @if(request()->routeIs('admin.assiduite')) aria-current="page" @endif>
      <i class="fas fa-calendar-check"></i>
      <span data-en="Attendance" data-fr="Assiduité">Attendance</span>
    </a>

    <div class="sidebar-divider"></div>
    <div class="sidebar-label" data-en="Administration" data-fr="Administration">Administration</div>

    <a href="{{ route('admin.employees') }}" class="sidebar-menu-item {{ request()->routeIs('admin.employees') ? 'active' : '' }}" @if(request()->routeIs('admin.employees')) aria-current="page" @endif>
      <i class="fas fa-users"></i>
      <span data-en="Employees" data-fr="Collaborateurs">Employees</span>
    </a>

    <a href="{{ route('admin.add-user') }}" class="sidebar-menu-item {{ request()->routeIs('admin.add-user') ? 'active' : '' }}" @if(request()->routeIs('admin.add-user')) aria-current="page" @endif>
      <i class="fas fa-user-plus"></i>
      <span data-en="Add User" data-fr="Ajouter Utilisateur">Add User</span>
    </a>

    <a href="{{ route('admin.departements') }}" class="sidebar-menu-item {{ request()->routeIs('admin.departements*') ? 'active' : '' }}" @if(request()->routeIs('admin.departements*')) aria-current="page" @endif>
      <i class="fas fa-building"></i>
      <span data-en="Departments" data-fr="Départements">Departments</span>
    </a>

    <a href="{{ route('admin.profile') }}" class="sidebar-menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}" @if(request()->routeIs('admin.profile')) aria-current="page" @endif>
      <i class="fas fa-user"></i>
      <span data-en="Profile" data-fr="Profil">Profile</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout" data-no-loading>
      @csrf
      <button type="submit" class="sidebar-menu-item">
        <i class="fas fa-sign-out-alt"></i>
        <span data-en="Logout" data-fr="Déconnexion">Logout</span>
      </button>
    </form>
  </nav>

  <div class="sidebar-footer">
    <p>&copy; {{ date('Y') }} UEMF &middot; Version 1.0</p>
  </div>
</aside>

<script src="{{ asset('js/ui.js') }}"></script>
