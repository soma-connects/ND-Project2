<x-guest-layout title="Signup" bodyclass="page signup">
    <!-- Header Section -->
    <section class="auth-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="font-playfair">Join Us! 🍄🐾</h1>
                <p class="hero-subtitle">Create an account to start your Paws, Petals & Fungi journey.</p>
            </div>
        </div>
    </section>

    <!-- Signup Form -->
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
                <form method="POST" action="{{ route('signup.post') }}" class="form-inner">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
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
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                            <i class="fas fa-eye password-toggle" aria-label="Toggle password visibility"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <p class="extra-link text-center">Already have an account? <a href="{{ route('login') }}" class="form-link">Login here</a></p>
            </div>
        </div>
    </section>
</x-guest-layout>