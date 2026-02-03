<nav class="navbar navbar-light bg-white border-bottom shadow-sm px-4">
    <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-link link-dark p-0 me-3 transition-all hover:bg-light rounded-circle shadow-sm" id="sidebar-toggle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #fff;">
                <i class="fas fa-outdent fs-5"></i>
            </button>
            <div class="d-none d-md-block">
                <span class="text-muted small fw-bold text-uppercase tracking-wider">Spencera Management</span>
            </div>
            <div class="d-md-none">
                <img src="https://spenceraceramica.com/images/logo/black.svg" alt="Logo" style="height: 25px;">
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-link link-dark text-decoration-none dropdown-toggle d-flex align-items-center p-0" type="button" data-bs-toggle="dropdown">
                    <div class="bg-black text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="d-none d-md-inline fw-semibold small">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
                    <li><a class="dropdown-item py-2 px-3 small fw-medium" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-2 opacity-50"></i> Profile</a></li>
                    <li><hr class="dropdown-divider opacity-5"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-3 small fw-medium text-danger">
                                <i class="fas fa-sign-out-alt me-2 opacity-50"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
