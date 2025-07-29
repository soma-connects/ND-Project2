<x-guest-layout title="Forgot Password" bodyclass="page forgot-password">
    <section class="auth-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="font-playfair">Reset Your Password 🍄🐾</h1>
                <p class="hero-subtitle">Enter your email to receive a password reset link.</p>
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
                <form method="POST" action="{{ route('password.email') }}" class="form-inner">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>
                <p class="extra-link text-center">Remember your password? <a href="{{ route('login') }}" class="form-link">Login here</a></p>
            </div>
        </div>
    </section>
</x-guest-layout>