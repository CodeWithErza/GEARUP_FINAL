<!-- Header -->
<div class="header mb-4">
    <h1 class="page-title">
        <i class="{{ $icon ?? 'fa-solid fa-gauge-high' }} me-2" style="color: var(--accent);"></i>
        {{ $title ?? 'Parts Shop Dashboard' }}
    </h1>
    
    <div class="user-info">
        <div class="date-time">
            <span id="current-date"></span>
            <span class="mx-2">|</span>
            <span id="current-time"></span>
        </div>
        
        <div class="employee-profile dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="profileDropdown">
                <div class="employee-avatar">
                    <i class="bi bi-person-circle" style="font-size: 1.5rem; color: var(--accent);"></i>
                </div>
                <span class="employee-name">{{ Auth::user()->name }}</span>
                <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem; color: var(--accent);"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="profileDropdown">
                <li class="dropdown-user-info">
                    <div class="dropdown-user-avatar">
                        <i class="bi bi-person-circle" style="font-size: 2rem; color: var(--accent);"></i>
                    </div>
                    <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileViewModal"><i class="fas fa-user me-2"></i> View Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item-form">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #111111;
}
</style> 