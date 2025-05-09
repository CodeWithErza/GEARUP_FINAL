<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="GearUp Logo" style="width: 120px; height: auto;">
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill" style="color: var(--accent);"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                <i class="fa-solid fa-cart-shopping" style="color: var(--accent);"></i>
                <span>Sales Order</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('orders.history') ? 'active' : '' }}" href="{{ route('orders.history') }}">
                <i class="fa-solid fa-history" style="color: var(--accent);"></i>
                <span>Order History</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="fa-solid fa-boxes-stacked" style="color: var(--accent);"></i>
                <span>Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('inventory*') ? 'active' : '' }}" href="{{ route('inventory') }}">
                <i class="fa-solid fa-warehouse" style="color: var(--accent);"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}" href="{{ route('reports.sales') }}">
                <i class="fa-solid fa-chart-line" style="color: var(--accent);"></i>
                <span>Sales Reports</span>
            </a>
        </li>
        @if(Auth::user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fa-solid fa-users" style="color: var(--accent);"></i>
                <span>Users</span>
            </a>
        </li>
        @endif
    </ul>
</div> 