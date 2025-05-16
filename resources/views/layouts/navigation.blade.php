<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-white border-bottom dark:bg-dark dark:navbar-dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto" />
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" @click="open = ! open">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" :class="{'show': open}">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Settings Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
