<x-app-layout title="{{ $title }}" bodyclass="{{ $bodyclass }}">
    <div class="container px-4 py-8">
        <h1 class="text-4xl font-bold mb-8   text-accent text-center font-playfair">{{ $product->name }}</h1>

        <!-- Notifications -->
        @if (session('success'))
            <div class="alert alert-success mb-6 flex justify-between items-center">
                {{ session('success') }}
                <button class="close" aria-label="Close alert">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-6 flex justify-between items-center">
                {{ session('error') }}
                <button class="close" aria-label="Close alert">×</button>
            </div>
        @endif

        <!-- Product Details -->
        <div class="product-details">
            <div class="product-image">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"  loading="lazy" onerror="this.src='https://placehold.co/300x200/cccccc/000000?text=Image+Not+Found'">
                @if ($product->is_new)
                    <span class="product-badge">New</span>
                @endif
                @if ($product->is_on_sale)
                    <span class="product-badge sale">Sale</span>
                @endif
            </div>
            <div class="product-info">
                <p class="text-lg"><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                <p class="text-2xl font-bold text-accent price">${{ number_format($product->price, 2) }}</p>
                <p class="product-description">{{ $product->description }}</p>
                <p><strong>Category:</strong> {{ ucfirst($product->category) }}</p>
                <p class="stock-info"><strong>Stock:</strong> {{ $product->stock > 0 ? $product->stock : 'Out of Stock' }}</p>
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form flex items-center gap-4" data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="quantity-selector-small input">
                    <button type="submit" class="btn btn-primary" {{ $product->stock == 0 ? 'disabled' : '' }}>Add to Cart</button>
                </form>
            </div>
        </div>

        <!-- Write-Up Section -->
        <section class="writeup-section">
            <h2 class="font-playfair">Why Buy {{ $product->name }}?</h2>
            <p class="product-description text-center">
                Discover the natural power of our mushroom-based products at Paws, Petals & Fungi. 
                {{ $product->name }} is crafted with premium, sustainably sourced ingredients to support your wellness journey. 
                Whether you're seeking to boost immunity, enhance focus, or promote relaxation, this product delivers nature’s finest benefits in every dose. 
                Experience the difference with our commitment to quality, purity, and eco-conscious practices.
            </p>
        </section>

        <!-- Recommended Products -->
        @if ($recommendedProducts && !$recommendedProducts->isEmpty())
            <section class="recommend-section">
                <h2 class="font-playfair">Explore Similar Products</h2>
                <p class="section-subtitle">Discover more from our {{ ucfirst($product->category) }} collection.</p>
                <div class="product-grid">
                    @foreach ($recommendedProducts as $recommended)
                        <div class="product-card">
                            <a href="{{ route('product', $recommended->id) }}" class="product-card-link">
                                <img src="{{ asset('storage/' . $recommended->image) }}" alt="{{ $recommended->name }}" class="product-image" loading="lazy" onerror="this.src='https://placehold.co/300x200/cccccc/000000?text=Image+Not+Found'">
                                @if ($recommended->is_new)
                                    <span class="product-badge">New</span>
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
                            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $recommended->id }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $recommended->id }}">
                                <div class="quantity-selector-small">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $recommended->stock }}" class="quantity-input">
                                    <button type="submit" class="btn btn-primary" {{ $recommended->stock == 0 ? 'disabled' : '' }}>Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script>
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const button = form.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        document.querySelector('.cart-count').textContent = data.cartCount;
                        showNotification(data.message || 'Product added to cart!', 'success');
                    } else {
                        showNotification(data.message || 'Failed to add to cart.', 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred while adding to cart.', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = 'Add to Cart';
                }
            });
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} mb-6 flex justify-between items-center`;
            notification.innerHTML = `
                ${message}
                <button class="close" aria-label="Close alert">×</button>
            `;
            document.querySelector('.container').prepend(notification);
            setTimeout(() => {
                notification.classList.add('fade-out');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        document.querySelectorAll('.close').forEach(button => {
            button.addEventListener('click', () => {
                const alert = button.parentElement;
                alert.classList.add('fade-out');
                setTimeout(() => alert.remove(), 500);
            });
        });
    </script>
</x-app-layout>