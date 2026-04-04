<!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/') }}" class="logo">
              <img
                src="{{ asset('assets/img/logo_easytix_new.png')}}"
                alt="EasyTix Logo"
                class="navbar-brand"
                height="45"
                style="margin-left: 10px;"
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

                  <!-- 1.5 Request Organizer -->
                  <li class="nav-item {{ request()->routeIs('admin.requestsOrganizer') ? 'active' : '' }}">
                      <a href="{{ route('admin.requestsOrganizer') }}">
                          <i class="fas fa-address-card"></i>
                          <p>Request Organizer</p>
                      </a>
                  </li>
                  
                  <!-- 2. Manajemen Event -->
                  <li class="nav-item {{ request()->routeIs('admin.manageEvents') ? 'active' : '' }}">
                      <a href="{{ route('admin.manageEvents') }}">
                          <i class="fas fa-calendar-check"></i>
                          <p>Manajemen Event</p>
                      </a>
                  </li>
                  
                  <!-- 3. Manajemen Tipe Tiket -->
                  <li class="nav-item {{ request()->routeIs('admin.manageTicketTypes') ? 'active' : '' }}">
                      <a href="{{ route('admin.manageTicketTypes') }}">
                          <i class="fas fa-ticket-alt"></i>
                          <p>Manajemen Tipe Tiket</p>
                      </a>
                  </li>

                   <!-- 4. Manajemen Kategori -->
                  <li class="nav-item {{ request()->routeIs('admin.manageCategories') ? 'active' : '' }}">
                    <a href="{{ route('admin.manageCategories') }}">
                        <i class="fas fa-th-list"></i>
                        <p>Manajemen Kategori</p>
                    </a>
                </li>

                  <!-- 5. Manajemen Performer -->
                  <li class="nav-item {{ request()->routeIs('admin.managePerformers') ? 'active' : '' }}">
                    <a href="{{ route('admin.managePerformers') }}">
                        <i class="fas fa-microphone"></i>
                        <p>Manajemen Performer</p>
                    </a>
                </li>

                <!-- 6. Manajemen Genre -->
                <li class="nav-item {{ request()->routeIs('admin.manageGenres') ? 'active' : '' }}">
                    <a href="{{ route('admin.manageGenres') }}">
                        <i class="fas fa-tags"></i>
                        <p>Manajemen Genre</p>
                    </a>
                </li>


                  <!-- 4. Fitur Laporan -->
                  <li class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                      <a href="{{ route('admin.reports') }}">
                          <i class="fas fa-chart-bar"></i>
                          <p>Fitur Laporan</p>
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
                    <li class="nav-item {{ request()->routeIs('organizer.myEvents') || request()->routeIs('organizer.myEventsDetail') || request()->routeIs('organizer.events') || request()->routeIs('organizer.selectEventVerification') || request()->routeIs('organizer.verifyTicketDetail') || request()->routeIs('organizer.verifySchedule') || request()->routeIs('organizer.attendees') ? 'active' : '' }}">
                        <a data-bs-toggle="collapse" href="#manageEvents">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Kelola Event</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('organizer.myEvents') || request()->routeIs('organizer.myEventsDetail') || request()->routeIs('organizer.events') || request()->routeIs('organizer.selectEventVerification') || request()->routeIs('organizer.verifyTicketDetail') || request()->routeIs('organizer.verifySchedule') || request()->routeIs('organizer.attendees') ? 'show' : '' }}" id="manageEvents">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('organizer.myEvents') ? 'active' : '' }}">
                                    <a href="{{ route('organizer.myEvents') }}">
                                        <span class="sub-item">Event Saya</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('organizer.selectEventVerification') ? 'active' : '' }}">
                                    <a href="{{ route('organizer.selectEventVerification') }}">
                                        <span class="sub-item">Verifikasi Tiket</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('organizer.events') ? 'active' : '' }}">
                                    <a href="{{ route('organizer.events') }}">
                                        <span class="sub-item">Request Event</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item {{ request()->routeIs('organizer.salesReport') ? 'active' : '' }}">
                        <a href="{{ route('organizer.salesReport') }}">
                            <i class="fas fa-chart-line"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>

              @else(Auth::check() && Auth::user()->role == 'user')
                  {{-- MENU user --}}
                  <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                      <a href="{{ route('user.dashboard') }}">
                          <i class="fas fa-home"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->routeIs('user.buyTickets') ? 'active' : '' }}">
                      <a href="{{ route('user.buyTickets') }}">
                          <i class="fas fa-ticket-alt"></i>
                          <p>Beli Tiket</p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->routeIs('cart.view') ? 'active' : '' }}">
                      <a href="{{ route('cart.view') }}">
                          <i class="fas fa-shopping-cart"></i>
                          <p>Keranjang
                              @if(session('cart') && count(session('cart')) > 0)
                                  <span class="badge badge-count bg-danger ms-2">{{ count(session('cart')) }}</span>
                              @endif
                          </p>
                      </a>
                  </li>
                  <li class="nav-item {{ request()->routeIs('user.myTickets') ? 'active' : '' }}">
                      <a href="{{ route('user.myTickets') }}">
                          <i class="fas fa-ticket-alt"></i>
                          <p>Tiket Saya</p>
                      </a>
                  </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->