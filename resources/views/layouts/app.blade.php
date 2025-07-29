@props(['title' => '', 'bodyclass' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? "$title | " : '' }}Paws, Petals & Fungi</title>
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('assets/img/default.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">    <link rel="stylesheet" href="{{ asset('style.css') }}">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body class="{{ $bodyclass }}">
    {{-- @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
            <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" id="error-message">
            {{ session('error') }}
            <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif --}}

    <x-layouts.header />
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>
    <x-layouts.footer />
    <div class="telegram">
        <a href="https://t.me/paws_petals_fungi" target="_blank" class="telegram-button" aria-label="Chat on Telegram">
            <i class="fab fa-telegram"></i> Chat on Telegram
        </a>
    </div>

    <script src="{{ asset('app.js') }}" defer></script>
</body>
</html>