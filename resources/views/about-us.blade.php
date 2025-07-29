<x-app-layout title="About Us" bodyclass="about-us">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs">
        <div class="container">
            <a href="{{ route('home') }}">Home</a> / <span>About Us</span>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="font-playfair">Paws, Petals & Fungi</h1>
                <p class="hero-subtitle">Discover the heart and soul behind our nature-inspired collections.</p>
                <a href="#our-story" class="btn btn-primary">Explore Our Story</a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container page-content about-us-content">
        <!-- Our Story Section -->
        <section class="our-story" id="our-story">
            <div class="story-grid">
                <div class="story-text">
                    <h2 class="font-playfair">Our Story</h2>
                    <p class="section-subtitle">A journey rooted in nature and passion.</p>
                    <p>Founded in 2023, Paws, Petals & Fungi is a celebration of the extraordinary. Our curated collections—whimsical caps, cozy sheets, and mystical shroom-themed treasures—reflect our deep love for nature’s beauty. Every product is crafted with care, blending creativity with sustainability.</p>
                    <p>Our team of nature enthusiasts is committed to bringing you high-quality, eco-conscious products that inspire joy and connection with the world around us.</p>
                    <a href="{{ route('shop') }}" class="btn btn-secondary mt-5">Shop Our Collections</a>
                </div>
                <div class="story-image">
                    <img src="https://placehold.co/600x400/4A7C59/FEFAE0?text=Our+Story" alt="Our Story" class="about-image">
                </div>
            </div>
        </section>

        <!-- Mission & Values Section -->
        <section class="our-mission">
            <h2 class="font-playfair">Our Mission & Values</h2>
            <p class="section-subtitle">Guided by purpose, grounded in principles.</p>
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-star"></i>
                    <h3>Quality</h3>
                    <p>We source only the finest materials to ensure every product meets our high standards.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-leaf"></i>
                    <h3>Sustainability</h3>
                    <p>Partnering with ethical suppliers to minimize our environmental footprint.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-users"></i>
                    <h3>Community</h3>
                    <p>Building a vibrant community of nature lovers who share our passion.</p>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="our-team">
            <h2 class="font-playfair">Meet Our Team</h2>
            <p class="section-subtitle">The passionate folks behind Paws, Petals & Fungi.</p>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://placehold.co/200x200/4A7C59/FEFAE0?text=Team+Member" alt="Team Member" class="team-image">
                    <h3>Jane Doe</h3>
                    <p class="team-role">Founder & Creative Director</p>
                    <p>A nature enthusiast with a vision to bring sustainable beauty to everyday life.</p>
                </div>
                <div class="team-member">
                    <img src="https://placehold.co/200x200/4A7C59/FEFAE0?text=Team+Member" alt="Team Member" class="team-image">
                    <h3>John Smith</h3>
                    <p class="team-role">Sustainability Lead</p>
                    <p>Championing eco-friendly practices and supplier partnerships.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <h2 class="font-playfair">Join Our Nature-Loving Community</h2>
            <p class="section-subtitle">Subscribe to our newsletter for updates, exclusive offers, and more.</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </section>
    </main>
</x-app-layout>