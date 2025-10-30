<!-- resources/views/home.blade.php -->
<x-app-layout title="Home" bodyclass="home">
    <section class="hero" style="background: url('{{ asset('assets/img/shroom6.jpg') }}') no-repeat center center/cover;">
        <div class="hero-content container">
            <h1>Welcome to Paws, Petals & Fungi</h1>
            <p class="hero-subtitle"></p>Natural, premium mushrooms and more, for a healthier and happy life.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Shop Now</a>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2>Our Featured Products</h2>
            <p class="section-subtitle">Handpicked for you.</p>
            <div class="product-grid">
                @forelse($products as $product)
                    <article class="product-card">
                        @if($product->is_new || $product->is_on_sale)
                            <span class="product-badge">{{ $product->is_new ? 'New' : 'Sale' }}</span>
                        @endif
                        <a href="{{ route('product', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-200 object-cover rounded" loading="lazy" onerror="this.src='https://placehold.co/300x300/cccccc/000000?text=Image+Not+Found'">
                            <h3>{{ $product->name }}</h3>
                            <p class="price">${{ number_format($product->price, 2) }}</p>
                            {{-- <div class="product-rating">
                                <span class="stars">★ {{ $product->average_rating ?? '0.0' }}</span>
                                <span class="review-count">({{ $product->review_count ?? 0 }} reviews)</span>
                            </div> --}}
                        </a>
                        <p class="sku">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        @if($product->stock < 5 && $product->stock > 0)
                            <p class="stock-info">Only {{ $product->stock }} left in stock!</p>
                        @elseif($product->stock == 0)
                            <p class="stock-info out-of-stock">Out of Stock</p>
                        @endif
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-primary add-to-cart" {{ $product->stock == 0 ? 'disabled' : '' }}>Add to Cart</button>
                        </form>
                        {{-- <button class="btn btn-secondary quick-view-btn" data-product-id="{{ $product->id }}">Quick View</button> --}}
                    </article>
                @empty
                    <p>No featured products available.</p>
                @endforelse
            </div>
            <div class="view-all">
                <a href="{{ route('shop') }}" class="btn btn-secondary">View All Products</a>
            </div>
        </div>
    </section>

   <section class="categories-section">
    <div class="container">
        <h2>Explore Our Collections</h2>
        <p class="section-subtitle">Discover our curated range of Pharma, mushrooms, and THC/LSD/DMT.</p>
        <div class="category-grid">
            <a href="{{ route('cap') }}" class="category-item">
                <img src="{{ asset('assets/img/mg1.jpeg') }}" alt="Caps" loading="lazy">
                <h3>Pharma</h3>
                <p>Boost wellness with our premium capsules.</p>
            </a>
            <a href="{{ route('sheet') }}" class="category-item">
                <img src="{{ asset('assets/img/mg2.jpeg') }}" alt="Sheets" loading="lazy">
                <h3>THC/LSD/DMT</h3>
                <p>Calming, natural sheets for you.</p>
            </a>
            <a href="{{ route('shroom') }}" class="category-item">
                <img src="{{ asset('assets/img/mg3.jpeg') }}" alt="Shrooms" loading="lazy">
                <h3>Shroom</h3>
                <p>Nutrient-rich product for health.</p>
            </a>
        </div>
    </div>
</section>

    <section class="about-section" style="background: url('{{ asset('assets/img/about.webp') }}') no-repeat center center/cover;">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>About Petals, Paws & Fungi</h2>
                    <p>We’re passionate about natural living, medicinal mushrooms, and vibrant flowers to enrich lives. Our products are sustainably sourced, crafted with love, and designed to bring joy to you.</p>
                    <a href="{{ route('aboutus') }}" class="btn btn-primary">Learn More</a>
                </div>
                <div class="about-image">
                    <img src="{{ asset('assets/img/default.png') }}" alt="About Us" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials-section">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <p class="section-subtitle">Hear from our wellness enthusiasts.</p>
            <div class="testimonials-grid">
                <div class="testimonial-item">
                    <p>"The Cordyceps Capsules gave me such an energy boost! "</p>
                    <h4>Sarah M.</h4>
                    <span class="stars">★★★★★</span>
                </div>
                <div class="testimonial-item">
                    <p>"The Chamomile Pet Sheet keeps my cat calm during storms. Amazing quality!"</p>
                    <h4>James T.</h4>
                    <span class="stars">★★★★★</span>
                </div>
                <div class="testimonial-item">
                    <p>"Beautiful Marigold Bouquet and fast delivery. Will shop again!"</p>
                    <h4>Emma L.</h4>
                    <span class="stars">★★★★★</span>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="newsletter-section">
        <div class="container">
            <h2>Join Our Newsletter</h2>
            <p class="section-subtitle">Stay updated with new products, and exclusive offers.</p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </section> --}}

    
</x-app-layout>