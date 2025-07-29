<x-guest-layout title="Login" bodyclass="page login">
    <!-- Header Section -->
    <section class="auth-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="font-playfair">Welcome Back! 🍄🐾</h1>
                <p class="hero-subtitle">Login to your Paws, Petals & Fungi account.</p>
            </div>
        </div>
    </section>

    <!-- Login Form -->
    <section class="auth-section">
        <div class="container">
            <div class="auth-form vine-border">
                @if (session('success'))
                    <div class="alert alert-success flex justify-between items-center">
                        {{ session('success') }}
                        <button class="close" aria-label="Close alert">×</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger flex justify-between items-center">
                        {{ session('error') }}
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
                <form action="{{ route('login.post') }}" method="POST" class="form-inner">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="Enter your email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required placeholder="Enter your password">
                            <i class="fas fa-eye password-toggle" aria-label="Toggle password visibility"></i>
                        </div>
                        @error('password')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="form-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="extra-link text-center">Don't have an account? <a href="{{ route('signup') }}" class="form-link">Sign up here</a></p>
            </div>
        </div>
    </section>
</x-guest-layout>