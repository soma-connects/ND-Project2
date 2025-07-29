<x-base-layout title="Cart for {{ $user->name ?? 'Unknown User' }}" bodyclass="admin-user-carts-show">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Cart for {{ $user->name ?? 'Unknown User' }}</h1>

        <!-- Notifications -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="close">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
                <button class="close">×</button>
            </div>
        @endif

        

        <!-- Cart Items Table -->
        <div class="activity-card">
            <div class="card-header">
                <h2 class="card-title">Cart Items</h2>
                <a href="{{ route('admin.user-carts.index') }}" class="view-all">Back to User Carts</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user->carts as $cart)
                            <tr>
                                <td>{{ $cart->product ? $cart->product->name : 'Product Not Found' }}</td>
                                <td>{{ $cart->product ? ucfirst($cart->product->category) : '-' }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ $cart->product ? '$' . number_format($cart->product->price, 2) : '-' }}</td>
                                <td>{{ $cart->product ? '$' . number_format($cart->product->price * $cart->quantity, 2) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-gray-600 dark:text-gray-400 text-center py-12">No items in this cart.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($user->carts->isNotEmpty())
                    <div class="summary-row total mt-4">
                        <span>Total:</span>
                        <span>${{ number_format($user->carts->sum(function ($cart) {
                            return $cart->product ? $cart->product->price * $cart->quantity : 0;
                        }), 2) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-base-layout>