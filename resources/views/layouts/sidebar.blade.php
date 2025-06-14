<div class="sidebar">
    <h4 class="text-center py-3"><img src="https://spenceraceramica.com/images/logo/white.svg" alt=""></h4>
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Dashboard
    </a>
    <a href="{{ route('parties.index') }}" class="{{ request()->routeIs('parties.index') ? 'active' : '' }}">
        <i class="fas fa-palette"></i> Party
    </a>
    <a href="{{ route('designs.index') }}" class="{{ request()->routeIs('designs.index') ? 'active' : '' }}">
        <i class="fas fa-palette"></i> Design
    </a>
    <a href="{{ route('finishes.index') }}" class="{{ request()->routeIs('finishes.index') ? 'active' : '' }}">
        <i class="fas fa-palette"></i> Finish
    </a>
    <a href="{{ route('sizes.index') }}" class="{{ request()->routeIs('sizes.index') ? 'active' : '' }}">
        <i class="fas fa-ruler"></i> Size
    </a>
     <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
        <i class="fas fa-shopping-cart"></i> Orders
    </a>
    <!-- <a class="nav-link {{ request()->routeIs('purchase_order_item.index') ? 'active' : '' }}" href="{{ route('purchase_order_pallets.index') }}">
        <i class="fas fa-pallet"></i> Planning
    </a> -->
    <a class="nav-link {{ request()->routeIs('purchase_order_item.list') ? 'active' : '' }}" href="{{ route('purchase_order_item.list') }}">
        <i class="fas fa-pallet"></i> Production & Planning
    </a>
    <a class="nav-link {{ request()->routeIs('purchase_order_pallets.index') ? 'active' : '' }}" href="{{ route('purchase_order_pallets.index') }}">
        <i class="fas fa-pallet"></i> Pallet Packing
    </a>
    <a class="nav-link {{ request()->routeIs('dispatches.index') ? 'active' : '' }}" href="{{ route('dispatches.index') }}">
        <i class="fas fa-truck"></i> Dispatch
    </a>
    <a class="nav-link {{ request()->routeIs('stock-pallets.report') ? 'active' : '' }}" href="{{ route('stock-pallets.report') }}">
        <i class="nav-icon fas fa-boxes"></i> Stock Pallet Report
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<script>
    // Simple script to add 'active' class to the current page's link
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar a');
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
</script>
