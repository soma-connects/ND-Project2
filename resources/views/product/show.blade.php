@php
    $categoryRoute = match($product->category) {
        'caps' => 'cap',
        'sheet' => 'sheet', 
        'shroom' => 'shroom',
        default => 'shop'
    };
@endphp

<x-app-layout title="{{ $title }}" bodyclass="{{ $bodyclass }}">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav">
            <div class="container">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><span>&raquo;</span></li>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li><span>&raquo;</span></li>
                    <li><a href="{{ route($categoryRoute) }}">{{ ucfirst($product->category) }}</a></li>
                    <li><span>&raquo;</span></li>
                    <li><span>{{ $product->name }}</span></li>
                </ul>
            </div>
        </nav>

        <!-- Notifications -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Product Detail Section -->
        <div class="product-detail-wrapper">
            <!-- Product Images Section -->
            <div class="product-image-gallery">
                <div class="main-image-container">
                    <img id="main-product-image" 
                         src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="main-image"
                         onerror="this.src='https://placehold.co/600x500/cccccc/000000?text=Image+Not+Found'">
                    
                    <!-- Product Badges -->
                    @if ($product->is_new)
                        <span class="product-badge new">New</span>
                    @endif
                    @if ($product->is_on_sale)
                        <span class="product-badge sale">Sale</span>
                    @endif
                </div>
                
                <!-- Thumbnail Images -->
                <div class="image-thumbnails">
                    <div class="thumbnail active">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }} thumbnail" 
                             onclick="changeMainImage(this.src)">
                    </div>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="product-info">
                <!-- Product Title -->
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <!-- Product Price -->
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                
                <!-- Product Meta -->
                <div class="product-meta">
                    <div>SKU: {{ $product->sku ?? 'N/A' }}</div>
                    <div>Category: {{ ucfirst($product->category) }}</div>
                    <div class="stock-info">
                        <span class="stock-indicator {{ $product->stock <= 0 ? 'out' : ($product->stock < 10 ? 'low' : '') }}"></span>
                        @if ($product->stock > 0)
                            {{ $product->stock }} in stock
                        @else
                            Out of stock
                        @endif
                    </div>
                </div>
                
                <!-- Product Description -->
                <div class="product-description">
                    <p>{{ $product->description ?: 'A premium quality product from our ' . ucfirst($product->category) . ' collection. Carefully selected for its exceptional quality and value.' }}</p>
                </div>
                    
                <!-- Add to Cart Section -->
                @if ($product->stock > 0)
                    <div class="quantity-controls">
                        <label class="quantity-label">Quantity:</label>
                        <div class="quantity-input-group">
                            <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   id="quantity-{{ $product->id }}"
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}" 
                                   readonly>
                            <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="add-to-cart-section">
                        <form action="{{ route('cart.add') }}" 
                              method="POST" 
                              class="add-to-cart-form" 
                              data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1" id="cart-quantity-{{ $product->id }}">
                            
                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                    
                    <div class="product-features">
                        <span class="feature-tag">Free Shipping</span>
                        <span class="feature-tag">30-Day Return</span>
                        <span class="feature-tag">Secure Checkout</span>
                    </div>
                @else
                    <div class="add-to-cart-section">
                        <button class="add-to-cart-btn" disabled>
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </button>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <!-- Product Tabs Section -->
        <div class="product-tabs">
            <div class="tabs-nav">
                <button class="tab-button active" data-tab="description">Description</button>
                <button class="tab-button" data-tab="specifications">Specifications</button>
                <button class="tab-button" data-tab="reviews">Reviews (24)</button>
                <button class="tab-button" data-tab="shipping">Shipping & Returns</button>
            </div>
            
            <div class="tab-contents">
                <!-- Description Tab -->
                <div class="tab-content active" id="description-tab">
                    <p>{{ $product->description ?: 'This premium quality product from our ' . ucfirst($product->category) . ' collection has been carefully selected for its exceptional quality and value. Our team ensures that every item meets our high standards before it reaches you.' }}</p>
                    <p>Whether you're a beginner or an experienced enthusiast, this {{ strtolower($product->category) }} product offers the perfect balance of quality, functionality, and value. Join thousands of satisfied customers who have made this their go-to choice.</p>
                </div>
                
                <!-- Specifications Tab -->
                <div class="tab-content" id="specifications-tab">
                    <table class="specifications-table">
                        <tr>
                            <th>SKU</th>
                            <td>{{ $product->sku ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ ucfirst($product->category) }}</td>
                        </tr>
                        <tr>
                            <th>Weight</th>
                            <td>1.2 lbs</td>
                        </tr>
                        <tr>
                            <th>Dimensions</th>
                            <td>12" x 8" x 4"</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-content" id="reviews-tab">
                    <p>Customer reviews coming soon...</p>
                </div>
                
                <!-- Shipping Tab -->
                <div class="tab-content" id="shipping-tab">
                    <h4>Shipping Information</h4>
                    <ul>
                        <li><strong>Free Standard Shipping</strong> on orders over $50</li>
                        <li><strong>Standard Shipping:</strong> 5-7 business days ($5.99)</li>
                        <li><strong>Express Shipping:</strong> 2-3 business days ($12.99)</li>
                    </ul>
                    
                    <h4>Return Policy</h4>
                    <ul>
                        <li><strong>30-day return window</strong> for unused items</li>
                        <li><strong>Free returns</strong> on orders over $50</li>
                        <li><strong>Refund processing:</strong> 3-5 business days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Products Section -->
    @if ($recommendedProducts && !$recommendedProducts->isEmpty())
        <section class="recommended-products">
            <div class="container">
                <h3>You Might Also Like</h3>
                
                <div class="recommended-grid">
                    @foreach ($recommendedProducts as $recommended)
                        <div class="product-card">
                            <a href="{{ route('product', $recommended->id) }}">
                                <img src="{{ asset('storage/' . $recommended->image) }}" 
                                     alt="{{ $recommended->name }}" 
                                     class="product-image"
                                     loading="lazy" 
                                     onerror="this.src='https://placehold.co/300x200/cccccc/000000?text=Image+Not+Found'">
                                
                                @if ($recommended->is_new)
                                    <span class="product-badge new">New</span>
                                @endif
                                @if ($recommended->is_on_sale)
                                    <span class="product-badge sale">Sale</span>
                                @endif
                                
                                <div class="product-card-content">
                                    <h3>{{ $recommended->name }}</h3>
                                    <p class="sku">SKU: {{ $recommended->sku ?? 'N/A' }}</p>
                                    <p class="price">${{ number_format($recommended->price, 2) }}</p>
                                </div>
                            </a>
                            
                            @if ($recommended->stock > 0)
                                <form action="{{ route('cart.add') }}" 
                                      method="POST" 
                                      class="add-to-cart-form" 
                                      data-product-id="{{ $recommended->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $recommended->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <button class="btn" disabled>
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- JavaScript for Enhanced Functionality -->
    <script>
        // Quantity Control Functions
        function changeQuantity(change) {
            const input = document.querySelector('#quantity-{{ $product->id }}');
            const hiddenInput = document.querySelector('#cart-quantity-{{ $product->id }}');
            const currentValue = parseInt(input.value);
            const newValue = currentValue + change;
            const minValue = parseInt(input.min);
            const maxValue = parseInt(input.max);
            
            if (newValue >= minValue && newValue <= maxValue) {
                input.value = newValue;
                if (hiddenInput) {
                    hiddenInput.value = newValue;
                }
            }
        }
        
        // Image Gallery Functions
        function changeMainImage(src) {
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                mainImage.src = src;
            }
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            if (event && event.target) {
                const clickedThumbnail = event.target.closest('.thumbnail');
                if (clickedThumbnail) {
                    clickedThumbnail.classList.add('active');
                }
            }
        }
        
        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    
                    // Update active tab button
                    document.querySelectorAll('.tab-button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Show corresponding tab content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    const targetTab = document.getElementById(tabName + '-tab');
                    if (targetTab) {
                        targetTab.classList.add('active');
                    }
                });
            });
            
            // Enhanced Add to Cart Animation
            const addToCartForms = document.querySelectorAll('.add-to-cart-form');
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    
                    // Add loading state
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                    button.disabled = true;
                    button.classList.add('animate');
                    
                    // Reset after a delay (the actual form submission will handle the response)
                    setTimeout(() => {
                        if (button) {
                            button.innerHTML = originalText;
                            button.disabled = false;
                            button.classList.remove('animate');
                        }
                    }, 2000);
                });
            });
        });
    </script>
</x-app-layout>
