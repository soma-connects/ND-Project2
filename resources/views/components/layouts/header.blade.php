<header class="site-header">
    <div class="container header-container">
        <div class="logo-left md:logo-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/cover.png') }}" class="logo" alt="Paws, Petals & Fungi Logo">
            </a>
        </div>
        <div class="header-actions-left hidden md:flex">
            <a href="{{ route('shop') }}" class="nav-link">Shop</a>
            <a href="{{ route('cap') }}" class="nav-link">Caps</a>
            <a href="{{ route('sheet') }}" class="nav-link">Chocolate Bar</a>
            <a href="{{ route('shroom') }}" class="nav-link">Shrooms</a>
            <a href="{{ route('aboutus') }}" class="nav-link">About</a>
        </div>
        <div class="header-actions-right hidden md:flex items-center">
            <a href="{{ route('contactus') }}" class="nav-link">Contact</a>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Search products..." required>
                <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
            </form>
            <a href="{{ route('cart') }}" class="icon-link" aria-label="Shopping Cart">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count">{{ $cartCount }}</span>
            </a>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Dashboard</a>
                @endif
                <a href="{{ route('logout') }}" class="icon-link" aria-label="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="icon-link" aria-label="Login"><i class="fas fa-user"></i></a>
                <a href="{{ route('signup') }}" class="nav-link">Signup</a>
            @endauth
        </div>
        <div class="header-actions-right-mobile md:hidden flex items-center">
            <a href="{{ route('cart') }}" class="icon-link" aria-label="Shopping Cart">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count">{{ $cartCount }}</span>
            </a>
            @auth
                <a href="{{ route('logout') }}" class="icon-link" aria-label="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="icon-link" aria-label="Login"><i class="fas fa-user"></i></a>
            @endauth
        </div>
        <button class="menu-toggle md:hidden" aria-label="Toggle menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile Navigation -->
    <div class="mobile-nav-backdrop" id="mobile-nav-backdrop"></div>
    <nav class="mobile-nav" id="mobile-nav">
        <a href="{{ route('shop') }}" class="nav-link">Shop All</a>
        <a href="{{ route('cap') }}" class="nav-link">Caps</a>
        <a href="{{ route('sheet') }}" class="nav-link">Chocolate Bar</a>
        <a href="{{ route('shroom') }}" class="nav-link">Shrooms</a>
        <a href="{{ route('aboutus') }}" class="nav-link">About</a>
        <a href="{{ route('contactus') }}" class="nav-link">Contact</a>
        <a href="{{ route('cart') }}" class="nav-link">Cart ({{ $cartCount }})</a>
        @auth
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Dashboard</a>
            @endif
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">Logout</a>
            <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            <a href="{{ route('signup') }}" class="nav-link">Signup</a>
        @endauth
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search products..." required>
            <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
        </form>
    </nav>
</header>