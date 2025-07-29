@props(['title' => '', 'bodyclass' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? "$title | " : '' }}{{ config('app.name', 'Paws, Petals & Fungi') }}</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="guest-page {{ $bodyclass }}">
    <header class="guest-header">
        <div class="container">
            <div class="logo-center">
                <a href="{{ route('home') }}">Paws, Petals & Fungi</a>
            </div>
        </div>
    </header>
    <main>
        {{ $slot }}
    </main>
   
    <script src="{{ asset('app.js') }}" defer></script>
</body>
</html>