<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <span class="brand-text fw-light">{{Auth::user()->name}}</span>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation" aria-label="Main navigation">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Companies -->
                <li class="nav-item">
                    <a href="{{ url('/companies') }}" class="nav-link">
                        <i class="nav-icon bi bi-building"></i>
                        <p>Companies</p>
                    </a>
                </li>

                <!-- Employees -->
                <li class="nav-item">
                    <a href="{{ url('/employees') }}" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Employees</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="{{ url('/logout') }}" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>