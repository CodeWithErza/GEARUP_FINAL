<!-- Report Navigation Component -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-underline border-0">
            <li class="nav-item">
                <a class="nav-link fw-bold {{ request()->routeIs('reports.sales') ? 'active' : '' }}" href="{{ route('reports.sales') }}">Sales</a>
            </li>
            {{-- Uncomment and modify these items when adding new report types
            <li class="nav-item">
                <a class="nav-link fw-bold {{ request()->routeIs('reports.expenses') ? 'active' : '' }}" href="{{ route('reports.expenses') }}">Expenses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold {{ request()->routeIs('reports.inventory') ? 'active' : '' }}" href="{{ route('reports.inventory') }}">Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold {{ request()->routeIs('reports.customers') ? 'active' : '' }}" href="{{ route('reports.customers') }}">Customers</a>
            </li>
            --}}
        </ul>
    </div>
</div>

<style>
    .nav-underline .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.5rem 0;
        margin-right: 2rem;
        position: relative;
    }
    .nav-underline .nav-link:hover,
    .nav-underline .nav-link.active {
        color: #000;
        background: none;
    }
    .nav-underline .nav-link::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: currentColor;
        transform: scaleX(0);
        transition: transform 0.2s;
    }
    .nav-underline .nav-link:hover::after {
        transform: scaleX(0.5);
    }
    .nav-underline .nav-link.active::after {
        transform: scaleX(1);
    }
</style> 