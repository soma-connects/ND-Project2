<x-base-layout>
    <div class="container">
        <div class="max-w-5xl mx-auto bg-secondary-bg rounded-lg shadow-xl p-8 mt-8">
            <h2 class="text-4xl font-bold mb-8 text-accent text-center font-playfair">User Carts</h2>
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
            @if ($users->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No users have items in their carts.</p>
            @else
                @foreach ($users as $user)
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-accent mb-4">{{ $user->name }}'s Cart</h3>
                        <div class="cart-layout">
                            @foreach ($user->carts as $cartItem)
                                <div class="cart-item">
                                    <img src="{{ asset('storage/' . $cartItem->product->image) }}" alt="{{ $cartItem->product->name }}" class="cart-item-image">
                                    <div class="cart-item-details">
                                        <span class="cart-item-title">{{ $cartItem->product->name }}</span>
                                        <p class="cart-item-price">${{ number_format($cartItem->product->price, 2) }} x {{ $cartItem->quantity }}</p>
                                        <p class="cart-item-total">Subtotal: ${{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.close').forEach(button => {
            button.addEventListener('click', function () {
                const alert = this.parentElement;
                alert.classList.add('fade-out');
                setTimeout(() => alert.remove(), 500);
            });
        });
    </script>
</x-base-layout>