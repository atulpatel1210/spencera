<div class="sidebar">
    <div class="d-flex align-items-center justify-content-between logo px-4">
        <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Logo">
        <button class="btn btn-link text-white d-lg-none p-0" id="sidebar-close">
            <i class="fas fa-times fs-4"></i>
        </button>
    </div>
    
    <div class="nav-links mt-3">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('parties.index') }}" class="{{ request()->routeIs('parties.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>Party</span>
        </a>
        <a href="{{ route('designs.index') }}" class="{{ request()->routeIs('designs.index') ? 'active' : '' }}">
            <i class="fas fa-drafting-compass"></i> <span>Design</span>
        </a>
        <a href="{{ route('finishes.index') }}" class="{{ request()->routeIs('finishes.index') ? 'active' : '' }}">
            <i class="fas fa-paint-roller"></i> <span>Finish</span>
        </a>
        <a href="{{ route('sizes.index') }}" class="{{ request()->routeIs('sizes.index') ? 'active' : '' }}">
            <i class="fas fa-ruler-combined"></i> <span>Size</span>
        </a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> <span>Orders</span>
        </a>
        <a href="{{ route('purchase_order_item.list') }}" class="{{ request()->routeIs('purchase_order_item.list') ? 'active' : '' }}">
            <i class="fas fa-industry"></i> <span>Production & Planning</span>
        </a>
        <a href="{{ route('purchase_order_pallets.index') }}" class="{{ request()->routeIs('purchase_order_pallets.index') ? 'active' : '' }}">
            <i class="fas fa-boxes-packing"></i> <span>Pallet Packing</span>
        </a>
        <a href="{{ route('dispatches.index') }}" class="{{ request()->routeIs('dispatches.index') ? 'active' : '' }}">
            <i class="fas fa-truck-fast"></i> <span>Dispatch</span>
        </a>
        <a href="{{ route('stock-pallets.report') }}" class="{{ request()->routeIs('stock-pallets.report') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i> <span>Stock Pallet Report</span>
        </a>
        
        <div class="sidebar-footer mt-auto pt-4 px-3">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-link text-danger border-0">
                <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
            </a>
        </div>
    </div>
</div>
