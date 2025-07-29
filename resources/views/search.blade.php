<x-app-layout title="Search Results" bodyclass="search-results-page">
    <div class="container px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-accent mt-5 btn text-center font-playfair" id="search-results-title">Search Results for "{{ $query }}"</h1>

        <!-- Sorting Controls (Optional, if using enhanced search method) -->
        <div class="sort-controls">
            <form action="{{ route('search') }}" method="GET">
                <input type="hidden" name="q" value="{{ $query }}">
                <select name="sort" onchange="this.form.submit()">
                    <option value="">Sort By</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Name: A-Z</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                </select>
            </form>
        </div>

        @if ($products->isEmpty())
            <p class="text-gray-600 dark:text-gray-400 text-center py-12">No products found for your search.</p>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card" role="article" aria-labelledby="product-{{ $product->id }}-title">
                        @if ($product->is_new)
                            <span class="product-badge" aria-label="New Product">New</span>
                        @endif
                        @if ($product->is_on_sale)
                            <span class="product-badge sale" aria-label="Product on Sale">Sale</span>
                        @endif
                        <a href="{{ route('product', $product->id) }}" aria-label="View {{ $product->name }} details">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" onerror="this.src='https://placehold.co/300x200/cccccc/000000?text=Image+Not+Found'">
                        </a>
                        <div class="product-card-content">
                            <a href="{{ route('product', $product->id) }}" id="product-{{ $product->id }}-title">
                                <h3>{{ $product->name }}</h3>
                            </a>
                            <p class="sku">{{ $product->sku ?? 'N/A' }}</p>
                            <p class="price">${{ number_format($product->price, 2) }}</p>
                            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="quantity-selector-small">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="input" aria-label="Quantity for {{ $product->name }}">
                                    <button type="submit" class="btn-primary add-to-cart" {{ $product->stock == 0 ? 'disabled' : '' }} aria-label="Add {{ $product->name }} to cart">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
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
                        showNotification(data.message || 'Product added to cart.', 'success');
                    } else {
                        showNotification(data.message || 'Failed to add to cart.', 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred while adding to cart.', 'error');
                }
            });
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} mb-6 flex justify-between items-center`;
            notification.innerHTML = `${message}<button class="close text-white">×</button>`;
            document.querySelector('.container').prepend(notification);
            setTimeout(() => notification.remove(), 3000);

            document.querySelectorAll('.close').forEach(button => {
                button.addEventListener('click', function () {
                    const alert = this.parentElement;
                    alert.classList.add('fade-out');
                    setTimeout(() => alert.remove(), 500);
                });
            });
        }
    </script>
</x-app-layout>