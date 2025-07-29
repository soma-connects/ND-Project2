<x-base-layout title="Products" bodyclass="admin-products-index">
    <div class="container">
        <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Products</h1>
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
                <h2 class="card-title">Product List</h2>
                <a href="{{ route('admin.products.create') }}" class="btn btn-glow">Create New Product</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="action-link">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
            <div class="mt-6">
                {{ $products->links('pagination::default') }}
            </div>
        </div>
    </div>
</x-base-layout>