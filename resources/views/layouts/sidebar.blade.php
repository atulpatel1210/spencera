<div class="sidebar d-flex flex-column h-100">
    <div class="d-flex align-items-center justify-content-between logo px-4">
        <a href="{{ route('dashboard') }}" class="p-0 border-0 bg-transparent">
            <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Logo" style="height: 35px;">
        </a>
        <button class="btn btn-link text-white d-lg-none p-0" id="sidebar-close">
            <i class="fas fa-times fs-4"></i>
        </button>
    </div>
    
    <div class="nav-links flex-grow-1 overflow-y-auto mt-3 custom-scrollbar">
        <div class="px-4 py-2 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.65rem;">General</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
        </a>
        
        <div class="px-4 py-2 mt-3 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.65rem;">Inventory & CRM</div>
        <a href="{{ route('parties.index') }}" class="{{ request()->routeIs('parties.index') ? 'active' : '' }}">
            <i class="fas fa-users-viewfinder"></i> <span>Party Management</span>
        </a>
        <a href="{{ route('designs.index') }}" class="{{ request()->routeIs('designs.index') ? 'active' : '' }}">
            <i class="fas fa-palette"></i> <span>Design Studio</span>
        </a>
        <a href="{{ route('finishes.index') }}" class="{{ request()->routeIs('finishes.index') ? 'active' : '' }}">
            <i class="fas fa-wand-magic-sparkles"></i> <span>Finish Details</span>
        </a>
        <a href="{{ route('sizes.index') }}" class="{{ request()->routeIs('sizes.index') ? 'active' : '' }}">
            <i class="fas fa-vector-square"></i> <span>Size Specs</span>
        </a>
        
        <div class="px-4 py-2 mt-3 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.65rem;">Operations</div>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i> <span>Order List</span>
        </a>
        <a href="{{ route('purchase_order_item.list') }}" class="{{ request()->routeIs('purchase_order_item.list') ? 'active' : '' }}">
            <i class="fas fa-gears"></i> <span>Production & Planning</span>
        </a>
        <a href="{{ route('purchase_order_pallets.index') }}" class="{{ request()->routeIs('purchase_order_pallets.index') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> <span>Pallet Packing</span>
        </a>
        <a href="{{ route('dispatches.index') }}" class="{{ request()->routeIs('dispatches.index') ? 'active' : '' }}">
            <i class="fas fa-truck-ramp-box"></i> <span>Dispatch Control</span>
        </a>
        
        <div class="px-4 py-2 mt-3 text-uppercase text-white-50 small fw-bold tracking-wider" style="font-size: 0.65rem;">Analytics</div>
        <a href="{{ route('stock-pallets.report') }}" class="{{ request()->routeIs('stock-pallets.report') ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i> <span>Stock Reports</span>
        </a>
    </div>

    <div class="sidebar-footer mt-auto p-3">
        <div class="bg-white/5 rounded-4 p-3 mb-2 d-none d-lg-block backdrop-blur-sm border border-white/5">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                <span class="small text-white-50">System Online</span>
            </div>
        </div>
        <form id="logout-sidebar-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-sidebar-form').submit();" class="logout-link text-danger border-0 rounded-4 w-100 d-flex align-items-center justify-content-center py-3 bg-danger/10 hover:bg-danger/20 transition-all">
            <i class="fas fa-power-off me-2"></i> <span class="fw-bold fw-bold small text-uppercase tracking-widest">Logout</span>
        </a>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 0px; }
    .tracking-wider { letter-spacing: 0.1em; }
    .bg-white\/5 { background-color: rgba(255, 255, 255, 0.05); }
    .bg-danger\/10 { background-color: rgba(220, 53, 69, 0.1); }
    .hover\:bg-danger\/20:hover { background-color: rgba(220, 53, 69, 0.2); }
    .logout-link { text-decoration: none !important; }
</style>
