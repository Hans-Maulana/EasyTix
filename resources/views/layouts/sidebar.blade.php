<!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="{{ asset('assets/img/kaiadmin/logo_EasyTix.png')}}"
                alt="navbar brand"
                class="navbar-brand"
                height="100"
                width="180"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              @if(Auth::check() && Auth::user()->role == 'admin')
                  {{-- MENU ADMIN --}}
                  <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                      <a href="{{ route('admin.dashboard') ?? '#' }}">
                          <i class="fas fa-home"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
                  <li class="nav-section">
                      <span class="sidebar-mini-icon">
                          <i class="fas fa-ellipsis-h"></i>
                      </span>
                      <h4 class="text-section">Sistem & Manajemen</h4>
                  </li>
                  
                  <!-- 1. Manajemen User -->
                  <li class="nav-item {{ request()->routeIs('admin.manageUsers') ? 'active' : '' }}">
                      <a href="{{ route('admin.manageUsers') }}">
                          <i class="fas fa-users"></i>
                          <p>Manajemen User</p>
                      </a>
                  </li>
                  
                  <!-- 2. Manajemen Event -->
                  <li class="nav-item {{ request()->routeIs('admin.manageEvents') ? 'active' : '' }}">
                      <a href="#">
                          <i class="fas fa-calendar-check"></i>
                          <p>Manajemen Event</p>
                      </a>
                  </li>
                  
                  <!-- 3. Manajemen Tiket -->
                  <li class="nav-item {{ request()->routeIs('admin.manageTickets') ? 'active' : '' }}">
                      <a href="#">
                          <i class="fas fa-ticket-alt"></i>
                          <p>Manajemen Tiket</p>
                      </a>
                  </li>

                  <!-- 4. Fitur Laporan -->
                  <li class="nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                      <a href="#">
                          <i class="fas fa-chart-bar"></i>
                          <p>Fitur Laporan</p>
                      </a>
                  </li>
                  
                  <!-- 5. Manajemen Banner -->
                  <li class="nav-item {{ request()->routeIs('admin.ManageBanners') ? 'active' : '' }}">
                      <a href="#">
                          <i class="fas fa-images"></i>
                          <p>Manajemen Banner</p>
                      </a>
                  </li>

                  <!-- 6. Atur Hak Organizer -->
                  <li class="nav-item {{ request()->routeIs('admin.ManageOrganiers') ? 'active' : '' }}">
                      <a href="#">
                          <i class="fas fa-user-shield"></i>
                          <p>Hak Akses Organizer</p>
                      </a>
                  </li>

                  <!-- 7. Logout (biasanya di header, tapi ditambahkan sesuai request) -->
                  <li class="nav-item mt-4">
                      <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="text-danger">
                          <i class="fas fa-sign-out-alt text-danger"></i>
                          <p>Logout</p>
                      </a>
                      <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </li>

              @elseif(Auth::check() && Auth::user()->role == 'organizer')
                  {{-- MENU ORGANIZER --}}
                  <li class="nav-item {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                      <a href="{{ route('organizer.dashboard') ?? '#' }}">
                          <i class="fas fa-home"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
                  <li class="nav-section">
                      <span class="sidebar-mini-icon">
                          <i class="fas fa-ellipsis-h"></i>
                      </span>
                      <h4 class="text-section">Manajemen Event</h4>
                  </li>
                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#events">
                          <i class="fas fa-calendar-alt"></i>
                          <p>Event Saya</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="events">
                          <ul class="nav nav-collapse">
                              <li>
                                  <a href="#">
                                      <span class="sub-item">Semua Event</span>
                                  </a>
                              </li>
                              <li>
                                  <a href="#">
                                      <span class="sub-item">Buat Event Baru</span>
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="nav-item">
                      <a href="#">
                          <i class="fas fa-ticket-alt"></i>
                          <p>Manajemen Tiket</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="#">
                          <i class="fas fa-chart-line"></i>
                          <p>Laporan Penjualan</p>
                      </a>
                  </li>

              @else
                  {{-- DEFAULT MENU JIKA BELUM LOGIN ATAU BUKAN ADMIN/ORGANIZER --}}
                  <li class="nav-item active">
                      <a href="#">
                          <i class="fas fa-home"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->