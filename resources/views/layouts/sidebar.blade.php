<style>
    .sidebar {
        background-color: #343a40;
        color: #fff;
        padding-bottom: 20px; /* Add padding at the bottom */
        min-height: 100vh; /* Ensure sidebar covers the full viewport height */
        display: flex;
        flex-direction: column;
    }

    .sidebar h4 {
        color: #fff;
        text-align: center;
        padding: 20px 10px;
        margin-bottom: 0; /* Remove default margin-bottom from heading */
        font-size: 1.25rem;
        font-weight: bold;
        border-bottom: 1px solid #495057; /* Add a border below the heading */
    }

    .sidebar a {
        color: #adb5bd;
        display: block;
        padding: 12px 16px;
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease; /* Smooth transition */
        border-left: 3px solid transparent; /* Add a transparent border */
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #495057;
        color: #fff;
        border-left-color: #007bff; /* Highlight with a blue border on hover/active */
    }

    .sidebar a i {
        margin-right: 8px; /* Add some space between icon and text */
        width: 1em; /* Ensure icons have a fixed width for alignment */
        text-align: center;
    }

    .sidebar form {
        margin-top: auto; /* Push the logout form to the bottom */
        padding: 10px;
        border-top: 1px solid #495057;
    }

    .sidebar form button {
        width: 100%;
        padding: 10px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-size: 1rem;
    }

    .sidebar form button:hover {
        background-color: #c82333;
    }

    /* Optional: Style for active link (if you have a way to determine the current page) */
    /* For example, if you add an 'active' class to the current link */
    .sidebar a.active {
        background-color: #007bff;
        color: #fff;
        border-left-color: #007bff;
    }
</style>

<div class="sidebar">
    <h4 class="text-center py-3">Spencera Admin</h4>
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
