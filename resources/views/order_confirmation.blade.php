<x-app-layout>
    <div class="container">
        <h1 class="text-4xl font-bold mb-8 text-accent font-playfair">Order Confirmation</h1>
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        <p class="text-lg mb-4">Thank you for your order! Your order ID is #{{ $order->id }}.</p>
        <p class="mb-4">We have received your payment receipt and will verify it soon. You will be notified once your payment is confirmed.</p>
        <div class="bg-gray-100 p-4 rounded">
            <h2 class="text-2xl font-bold mb-4">Order Details</h2>
            <p><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</p>
            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Name:</strong> {{ $order->name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            @if ($order->phone)
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
            @endif
            <p><strong>Address:</strong> {{ $order->address }}</p>
            <h3 class="text-xl font-bold mt-4 mb-2">Order Items</h3>
            @foreach ($order->items as $item)
                <div class="flex justify-between mb-2">
                    <span>{{ $item->product->name }} (SKU: {{ $item->product->sku ?? 'N/A' }}) (x{{ $item->quantity }})</span>
                    <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
            @if ($order->receipt)
                <h3 class="text-xl font-bold mt-4 mb-2">Payment Receipt</h3>
                <img src="{{ asset('storage/' . $order->receipt->receipt_path) }}" alt="Payment Receipt" class="max-w-xs rounded">
            @endif
        </div>
        <a href="{{ route('shop') }}" class="btn-glow mt-4">Continue Shopping</a>
    </div>
</x-app-layout>