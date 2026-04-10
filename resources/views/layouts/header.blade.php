 <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('assets/img/logo_easy_tix.jpeg') }}" alt="EasyTix" height="45" class="me-2" style="border-radius: 8px;">
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
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                @auth
                @if(Auth::user()->role == 'user')
                <li class="nav-item topbar-icon hidden-caret">
                  <a class="nav-link" href="{{ route('cart.view') }}" title="Keranjang">
                    <i class="fas fa-shopping-cart"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                    <span class="notification">{{ count(session('cart')) }}</span>
                    @endif
                  </a>
                </li>
                @endif
                @php
                    $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                    $notifications = Auth::user()->notifications()->latest()->take(5)->get();
                @endphp
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="notifDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-bell"></i>
                    @if($unreadCount > 0)
                    <span class="notification">{{ $unreadCount }}</span>
                    @endif
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
                    <li>
                      <div class="dropdown-title">
                        Anda memiliki {{ $unreadCount }} notifikasi baru
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          @forelse($notifications as $notif)
                          <a href="{{ $notif->link ?? '#' }}">
                            <div class="notif-icon notif-{{ $notif->type }}" style="min-width: 40px; min-height: 40px; flex-shrink: 0;">
                              <i class="fa fa-{{ $notif->type === 'success' ? 'check' : ($notif->type === 'offer' ? 'tag' : 'info') }}"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block text-dark fw-bold"> {{ $notif->title }} </span>
                              <span class="time small">{{ $notif->created_at->diffForHumans() }}</span>
                              <div class="message text-muted small">{{ Str::limit($notif->message, 40) }}</div>
                            </div>
                          </a>
                          @empty
                          <div class="p-3 text-center text-muted small">Tidak ada notifikasi</div>
                          @endforelse
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="{{ route('notifications.index') }}">
                        Lihat semua notifikasi <i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                @endauth

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                        <img
                          src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F4D03F&color=000"
                          alt="avatar"
                          class="avatar-img rounded-circle"
                          style="width: 100%; height: 100%; object-fit: cover;"
                        />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F4D03F&color=000"
                              alt="image profile"
                              class="avatar-img rounded"
                              style="width: 100%; height: 100%; object-fit: cover;"
                            />
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</h4>
                            <p class="text-muted">{{ Auth::check() ? Auth::user()->email : '-' }}</p>
                            <a
                              href="{{ route('profile.show') }}"
                              class="btn btn-xs btn-secondary btn-sm"
                              >Profile Saya</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('notifications.index') }}">Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Pengaturan Akun</a>
                        <div class="dropdown-divider"></div>
                        
                        <!-- Form Logout Laravel -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" data-confirm="Apakah Anda yakin ingin keluar dari akun ini?">Logout</button>
                        </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>