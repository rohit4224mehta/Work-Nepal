<header>
    @php
        $user = Auth::user();
        $isAuth = Auth::check();
        $role = $isAuth ? $user->getRoleNames()->first() : null;
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow fixed-top">
        <div class="container-xl px-3 px-lg-4">

            <!-- Logo – role-aware redirect -->
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
               href="{{ 
                   !$isAuth ? route('home') :
                   match ($role) {
                       'admin'      => route('admin.dashboard'),
                       'employer'   => route('employer.dashboard'),
                       'job_seeker' => route('seeker.dashboard'),
                       default      => route('role.choose'),
                   }
               }}">
                <i class="fas fa-briefcase fs-4"></i>
                <span>WorkNepal</span>
            </a>

            <!-- Toggler button for mobile -->
            <button class="navbar-toggler" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarMain" 
                    aria-controls="navbarMain" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarMain">

                <!-- Left: Public / Role-specific menu -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 gap-lg-3">

                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('jobs.index') }}">
                                <i class="fas fa-search me-1"></i> Jobs
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-warning fw-medium"
                               href="{{ route('jobs.index', ['source_type' => 'foreign']) }}">
                                <i class="fas fa-globe-asia me-1"></i> Foreign Jobs
                            </a>
                        </li>
                    @endguest

                    @auth
                        @if ($role === 'job_seeker')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('jobs.index') }}">
                                    Find Jobs
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seeker.applications') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('seeker.applications') }}">
                                    My Applications
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seeker.bookmarks') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('seeker.bookmarks') }}">
                                    Bookmarks
                                </a>
                            </li>

                        @elseif ($role === 'employer')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employer.jobs.*') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('employer.jobs.index') }}">
                                    My Jobs
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employer.jobs.create') }}">
                                    <i class="fas fa-plus me-1"></i> Post Job
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employer.applicants') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('employer.applicants') }}">
                                    Applicants
                                </a>
                            </li>

                        @elseif ($role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.jobs.pending') ? 'active fw-semibold' : '' }}"
                                   href="{{ route('admin.jobs.pending') }}">
                                    Pending Jobs
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Right side: Notifications + Profile / Auth buttons -->
                <ul class="navbar-nav ms-auto align-items-center gap-2 gap-lg-3">

                    @auth
                        <!-- Notification Bell -->
                        <li class="nav-item position-relative">
                            <a class="nav-link text-white px-2" href="#" aria-label="Notifications">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-2 py-1 small">
                                    3
                                </span>
                            </a>
                        </li>

                        <!-- Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center p-0 gap-2" 
                               href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle shadow-sm" 
                                         width="38" height="38" 
                                         style="object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                                @else
                                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold" 
                                         style="width:38px; height:38px; font-size:1.3rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif

                                <span class="d-none d-md-inline fw-medium text-white">
                                    {{ $user->name }}
                                </span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-2" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2 text-muted"></i> Edit Profile
                                    </a>
                                </li>

                                @if ($role === 'job_seeker')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('seeker.dashboard') }}">
                                            <i class="fas fa-home me-2 text-muted"></i> Dashboard
                                        </a>
                                    </li>
                                @elseif ($role === 'employer')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('employer.dashboard') }}">
                                            <i class="fas fa-briefcase me-2 text-muted"></i> Employer Dashboard
                                        </a>
                                    </li>
                                @elseif ($role === 'admin')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2 text-muted"></i> Admin Dashboard
                                        </a>
                                    </li>
                                @endif

                                <li><hr class="dropdown-divider my-2"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest buttons -->
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm px-4 py-2 fw-medium" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm px-4 py-2 fw-medium" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Increased spacer to push content below fixed navbar (76px + shadow) -->
    <div style="height: 90px;"></div>
</header>