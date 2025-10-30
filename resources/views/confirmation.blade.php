<x-app-layout title="Order Confirmation" bodyclass="order-confirmation">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Order Confirmation</h1>
        
        <div class="alert alert-success mb-8">
            <i class="fas fa-check-circle"></i> Your order has been placed successfully! 
            We'll review your payment receipt and confirm your order within 24 hours.
            <button class="close">×</button>
        </div>

        <div class="activity-card mb-8">
            <h2 class="card-title">Order Details</h2>
            <div class="p-6">
                <p class="mb-4"><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p class="mb-4"><strong>Status:</strong> <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                <p class="mb-4"><strong>Customer:</strong> 
                    @if($order->isGuestOrder())
                        {{ $order->guest_name }} <span class="text-sm text-gray-500">(Guest Order)</span><br>
                        <small class="text-gray-600">Email: {{ $order->guest_email }}</small><br>
                        @if($order->guest_phone)
                            <small class="text-gray-600">Phone: {{ $order->guest_phone }}</small><br>
                        @endif
                        <small class="text-gray-600">Address: {{ $order->guest_address }}</small>
                    @else
                        {{ $order->user->name }}<br>
                        <small class="text-gray-600">{{ $order->user->email }}</small>
                    @endif
                </p>
                <p class="mb-4"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}</p>
            </div>
        </div>

        <div class="activity-card mb-8">
            <h2 class="card-title">Items Ordered</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-right mt-6 p-6 border-t border-gray-200">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->subtotal < 50)
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$5.00</span>
                    </div>
                @endif
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('shop') }}" class="btn btn-primary mr-4">Continue Shopping</a>
            @if(!$order->isGuestOrder())
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">View Order Status</a>
            @endif
        </div>

        <div class="alert alert-info mt-8">
            <i class="fas fa-info-circle"></i> 
            <strong>What happens next?</strong><br>
            1. Our team will verify your payment receipt<br>
            2. Once verified, your order status will be updated to "Verified"<br>
            3. We'll process and ship your order<br>
            4. You'll receive tracking information via email
        </div>
    </div>
</x-app-layout>