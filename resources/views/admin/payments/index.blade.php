<x-base-layout title="Payment Receipts" bodyclass="admin-payments-index">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Payment Receipts</h1>
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
                <h2 class="card-title">Receipts</h2>
                <a href="{{ route('admin.dashboard') }}" class="view-all">Back to Dashboard</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Receipt</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipts as $receipt)
                            <tr>
                                <td>{{ $receipt->order_id }}</td>
                                <td>{{ $receipt->order->user->name }}</td>
                                <td><a href="{{ asset('storage/' . $receipt->receipt_path) }}" target="_blank" class="action-link">View Receipt</a></td>
                                <td><span class="status status-{{ $receipt->status }}">{{ ucfirst($receipt->status) }}</span></td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $receipt->id) }}" class="action-link">View Details</a>
                                    @if ($receipt->status === 'pending')
                                        <form action="{{ route('admin.payments.verify', $receipt->id) }}" method="POST" class="payment-process-form inline-block">
                                            @csrf
                                            <select name="status" class="form-control" required>
                                                <option value="verified">Verify</option>
                                                <option value="rejected">Reject</option>
                                            </select>
                                            <textarea name="notes" class="form-control mt-2" placeholder="Add notes (optional)"></textarea>
                                            <button type="submit" class="btn btn-glow mt-2">Submit</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $receipts->links('pagination::default') }}
            </div>
        </div>
    </div>
</x-base-layout>