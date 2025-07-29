<x-base-layout title="Payment Receipt #{{ $receipt->id }}" bodyclass="admin-payments-show">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Payment Receipt #{{ $receipt->id }}</h1>
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
        <div class="activity-card">
            <div class="card-header">
                <h2 class="card-title">Receipt Details</h2>
                <a href="{{ route('admin.payments.index') }}" class="view-all">Back to Payments</a>
            </div>
            <div class="p-6">
                <p class="mb-4"><strong>Order ID:</strong> {{ $receipt->order_id }}</p>
                <p class="mb-4"><strong>User:</strong> {{ $receipt->order->user->name }}</p>
                <p class="mb-4"><strong>Receipt:</strong> <a href="{{ asset('storage/' . $receipt->receipt_path) }}" target="_blank" class="action-link">View Receipt</a></p>
                <p class="mb-4"><strong>Status:</strong> <span class="status status-{{ $receipt->status }}">{{ ucfirst($receipt->status) }}</span></p>
                @if ($receipt->notes)
                    <p class="mb-4"><strong>Notes:</strong> {{ $receipt->notes }}</p>
                @endif
                @if ($receipt->status === 'pending')
                    <form action="{{ route('admin.payments.verify', $receipt->id) }}" method="POST" class="payment-process-form mt-6">
                        @csrf
                        <div class="form-group">
                            <label for="status" class="form-label">Update Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="verified">Verify</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" placeholder="Add notes..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-glow">Submit</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-base-layout>