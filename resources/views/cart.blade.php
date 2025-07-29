<x-app-layout title="Cart" bodyclass="cart-page">
    <div class="container">
        <div class="activity-card max-w-4xl mx-auto">
            <h2 class="font-playfair text-4xl font-bold mb-8 mt-5 btn text-accent text-center">Your Cart</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <button class="close">×</button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                    <button class="close">×</button>
                </div>
            @endif
            @if ($cartItems->isEmpty())
                <p class="text-gray-600 dark:text-gray-400 text-center py-12">Your cart is empty or contains unavailable items.</p>
            @else
                <div class="cart-items">
                    @foreach ($cartItems as $cartItem)
                        @if ($cartItem->product)
                            <div class="cart-item" data-product-id="{{ $cartItem->product_id }}">
                                @if ($cartItem->product->is_new)
                                    <span class="product-badge">New</span>
                                @endif
                                @if ($cartItem->product->is_on_sale)
                                    <span class="product-badge sale">Sale</span>
                                @endif
                                <img src="{{ asset('storage/' . $cartItem->product->image) }}" alt="{{ $cartItem->product->name }}" class="cart-item-image" loading="lazy" onerror="this.src='https://placehold.co/100x100/cccccc/000000?text=Image+Not+Found'">
                                <div class="cart-item-details">
                                    <a href="{{ route('product', $cartItem->product_id) }}" class="cart-item-title">{{ $cartItem->product->name }}</a>
                                    <p class="sku">SKU: {{ $cartItem->product->sku ?? 'N/A' }}</p>
                                    <p class="cart-item-price">${{ number_format($cartItem->product->price, 2) }}</p>
                                    <div class="quantity-selector">
                                        <form action="{{ route('cart.update', $cartItem->product_id) }}" method="POST" class="add-to-cart-form" data-product-id="{{ $cartItem->product_id }}">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->product->stock }}" class="form-control quantity-input">
                                            <button type="submit" class="btn btn-secondary">Update</button>
                                        </form>
                                    </div>
                                    <p class="cart-item-total">Subtotal: ${{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</p>
                                    <form action="{{ route('cart.remove', $cartItem->product_id) }}" method="POST" class="remove-item-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-item-btn" data-product-id="{{ $cartItem->product_id }}">×</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="cart-summary">
                    <h3 class="card-title">Cart Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="subtotal">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="total">${{ number_format($total, 2) }}</span>
                    </div>
                    @auth
                        <a href="{{ route('cart.checkout') }}" class="btn btn-glow mt-5">Proceed to Checkout</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-glow mt-5">Login to Checkout</a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>