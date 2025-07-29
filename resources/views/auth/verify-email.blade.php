<x-guest-layout title="Verify Email" bodyclass="page verify-email">
    <section class="auth-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="font-playfair">Verify Your Email 🍄🐾</h1>
                <p class="hero-subtitle">A verification link has been sent to your email.</p>
            </div>
        </div>
    </section>
    <section class="auth-section">
        <div class="container">
            <div class="auth-form vine-border">
                @if (session('success'))
                    <div class="alert alert-success flex justify-between items-center">
                        {{ session('success') }}
                        <button class="close" aria-label="Close alert">×</button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger flex justify-between items-center">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span><br>
                        @endforeach
                        <button class="close" aria-label="Close alert">×</button>
                    </div>
                @endif
                <form method="POST" action="{{ route('verification.resend') }}" class="form-inner">
                    @csrf
                    <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                </form>
                <p class="extra-link text-center"><a href="{{ route('logout') }}" class="form-link">Log out</a></p>
            </div>
        </div>
    </section>
</x-guest-layout>