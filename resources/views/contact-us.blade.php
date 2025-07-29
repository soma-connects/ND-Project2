<x-app-layout title="Contact Us" bodyclass="contact-us">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs">
        <div class="container">
            <a href="{{ route('home') }}">Home</a> / <span>Contact Us</span>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="font-playfair">Get in Touch</h1>
                <p class="hero-subtitle">We’re here to answer your questions and connect with our nature-loving community.</p>
                <a href="#contact-form" class="btn btn-primary">Send Us a Message</a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container page-content contact-us-content">
        <section class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form" id="contact-form">
                <h2 class="font-playfair">Send Us a Message</h2>
                <p class="section-subtitle">Have a question or feedback? We’d love to hear from you!</p>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <button class="close" aria-label="Close alert">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                        <button class="close" aria-label="Close alert">&times;</button>
                    </div>
                @endif
                <form action="{{ route('contact.submit') }}" method="POST" class="contact-form-inner">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="Your Name">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="Your Email">
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required placeholder="Your Message">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

            <!-- Contact Details -->
            <div class="contact-details">
                <h2 class="font-playfair">Contact Information</h2>
                <p class="section-subtitle">Reach us through your preferred channel.</p>
                <div class="contact-info-grid">
                    <div class="contact-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Address</h4>
                            <span>123 Nature Lane, Green Valley</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h4>Phone</h4>
                            <span><a href="tel:+15551234567">+1 (555) 123-4567</a></span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <span><a href="mailto:hello@pawspetalsfungi.com">nfo@petalpawsfungi.com</a></span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Hours</h4>
                            <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                        </div>
                    </div>
                </div>
                <!-- Social Media Links -->
                {{-- <div class="contact-social">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div> --}}
            </div>
        </section>

        <!-- Map Section (Optional) -->
        <section class="contact-map">
            <h2 class="font-playfair">Find Us</h2>
            <p class="section-subtitle">Visit our location or explore virtually.</p>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.086735523562!2d-122.4194154846813!3d37.77492927975966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDQ2JzI5LjciTiAxMjLCsDI1JzA5LjkiVw!5e0!3m2!1sen!2sus!4v1634567890123!5m2!1sen!2sus" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </section>
    </main>
</x-app-layout>