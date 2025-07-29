<x-base-layout title="User Carts" bodyclass="admin-user-carts-index">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">User Carts</h1>

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

        <!-- User Carts Table -->
        <div class="activity-card">
            <div class="card-header">
                <h2 class="card-title">User Carts</h2>
                <a href="{{ route('admin.dashboard') }}" class="view-all">Back to Dashboard</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->carts->sum('quantity') }}</td>
                                <td>
                                    <a href="{{ route('admin.user-carts.show', $user->id) }}" class="action-link">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-600 dark:text-gray-400 text-center py-12">No users with active carts.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (!empty($guestCarts))
            <div class="activity-card mt-6">
                <div class="card-header">
                    <h2 class="card-title">Guest Carts</h2>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Session ID</th>
                                <th>Total Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($guestCarts as $sessionId => $cart)
                                <tr>
                                    <td>{{ $sessionId }}</td>
                                    <td>{{ count($cart) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-base-layout>