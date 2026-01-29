<nav class="navbar navbar-light bg-white border-bottom shadow-sm px-3">
    <div class="d-flex align-items-center">
        <button class="btn btn-link link-dark p-0 me-3" id="sidebar-toggle">
            <i class="fas fa-bars fs-4"></i>
        </button>
        <span class="navbar-brand mb-0 h1">Welcome, {{ Auth::user()->name }}</span>
    </div>
</nav>
