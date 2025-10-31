<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand text-center py-3">
        <span class="brand-text fw-light">{{ Auth::user()->name ?? 'Guest' }}</span>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation" aria-label="Main navigation">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" 
                       class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Companies -->
                <li class="nav-item">
                    <a href="{{ url('/companies') }}" 
                       class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-building"></i>
                        <p>Companies</p>
                    </a>
                </li>

                <!-- Employees -->
                <li class="nav-item">
                    <a href="{{ url('/employees') }}" 
                       class="nav-link {{ request()->is('employees*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Employees</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100 {{ request()->is('logout') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sign-out-alt" style="color: #c2c7d0;"></i>
                            <p style="color: #c2c7d0;">Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
