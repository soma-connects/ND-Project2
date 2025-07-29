@props(['title' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? "$title | " : '' }}Paws, Petals & Fungi - Admin</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
</head>
<body class="admin">
    <div class="flex min-h-screen">
            <button class="menu-toggle toggle-edit" aria-expanded="false">
                    <i class="fas fa-bars"></i>
            </button>
        <aside class="sidebar" id="sidebar">
            
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-leaf"></i>
                    <span>Shroom Admin</span>
                </a>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-title">Management</div>
                <a href="{{ route('admin.products.index') }}" class="menu-item"><i class="fas fa-box"></i><span>Products</span></a>
                <a href="{{ route('admin.user-carts.index') }}" class="menu-item"><i class="fas fa-shopping-cart"></i><span>User Carts</span></a>
                <a href="{{ route('admin.payments.index') }}" class="menu-item"><i class="fas fa-credit-card"></i><span>Payments</span></a>
                <a href="{{ route('admin.users.index') }}" class="menu-item"><i class="fas fa-users"></i><span>Users</span></a>
                <a href="{{ route('home') }}" class="menu-item"><i class="fas fa-home"></i><span>Home</span></a>
            </nav>
        </aside>
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>
    <script src="{{ asset('app.js') }}" defer></script>
</body>
</html>