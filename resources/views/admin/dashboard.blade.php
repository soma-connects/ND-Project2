<x-base-layout title="Admin Dashboard">
    <div class="container">
        <h1 class="font-playfair h1-edit ">Admin Dashboard</h1>
        

        <!-- Notifications -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="close" onclick="this.parentElement.classList.add('fade-out')">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
                <button class="close" onclick="this.parentElement.classList.add('fade-out')">×</button>
            </div>
        @endif

        <!-- Overview Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Total Products</div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <a href="{{ route('admin.products.index') }}" class="stat-link">Manage Products</a>
            </div>
            <div class="stat-card">
                <div class="stat-title">Pending Orders</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
                <a href="{{ route('admin.payments.index') }}" class="stat-link">View Orders</a>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <a href="{{ route('admin.users.index') }}" class="stat-link">Manage Users</a>
            </div>
        </div>

        <!-- Quick Access Links -->
        <div class="quick-actions">
            <h2 class="section-title">Quick Access</h2>
            <div class="action-buttons">
                <a href="{{ route('admin.products.index') }}" class="action-button"><i class="fas fa-box"></i> Manage Products</a>
                <a href="{{ route('admin.user-carts.index') }}" class="action-button"><i class="fas fa-shopping-cart"></i> View User Carts</a>
                <a href="{{ route('admin.images.index') }}" class="action-button"><i class="fas fa-image"></i> Manage Images</a>
                <a href="{{ route('admin.payments.index') }}" class="action-button"><i class="fas fa-credit-card"></i> Manage Payments</a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-grid">
            <!-- Recent Products -->
            <div class="activity-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Products</h2>
                    <a href="{{ route('admin.products.index') }}" class="view-all">View All</a>
                </div>
                @if ($recentProducts->isEmpty())
                    <p>No products added yet.</p>
                @else
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ ucfirst($product->category) }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="action-link">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-link delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Orders -->
            <div class="activity-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Orders</h2>
                    <a href="{{ route('admin.payments.index') }}" class="view-all">View All</a>
                </div>
                @if ($recentOrders->isEmpty())
                    <p>No orders placed yet.</p>
                @else
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user ? $order->user->name : $order->name }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent User Carts -->
        <div class="activity-card">
            <div class="card-header">
                <h2 class="card-title">Recent User Carts</h2>
                <a href="{{ route('admin.user-carts.index') }}" class="view-all">View All</a>
            </div>
            @if ($recentCarts->isEmpty())
                <p>No active user carts.</p>
            @else
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Total Items</th>
                                <th>Total Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentCarts as $user)
                                <tr>
                                    <td>{{ $user->name ?? 'Unknown User' }}</td>
                                    <td>{{ $user->carts->sum('quantity') }}</td>
                                    <td>
                                        ${{ number_format($user->carts->sum(function ($cart) {
                                            return $cart->product ? $cart->product->price * $cart->quantity : 0;
                                        }), 2) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user-carts.show', $user->id) }}" class="action-link">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-base-layout>