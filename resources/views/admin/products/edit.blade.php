<x-base-layout title="Edit Product" bodyclass="admin-products-edit">
    <div class="container">
        <div class="admin-form max-w-3xl mx-auto">
            <h2 class="font-playfair text-4xl font-bold mb-8 text-accent text-center">Edit Product</h2>
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
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="form-control" required>
                        @error('sku')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $product->price) }}" class="form-control" required>
                        @error('price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required>
                        @error('stock')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" disabled>Select Category</option>
                        <option value="shroom" {{ old('category', $product->category) == 'shroom' ? 'selected' : '' }}>Shrooms</option>
                        <option value="cap" {{ old('category', $product->category) == 'cap' ? 'selected' : '' }}>Capsules</option>
                        <option value="sheet" {{ old('category', $product->category) == 'sheet' ? 'selected' : '' }}>Sheets</option>
                        <option value="pet_food" {{ old('category', $product->category) == 'pet_food' ? 'selected' : '' }}>Pet Food</option>
                        <option value="flowers" {{ old('category', $product->category) == 'flowers' ? 'selected' : '' }}>Flowers</option>
                    </select>
                    @error('category')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" class="product-image mt-2">
                    @endif
                    @error('image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="6" class="form-control">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" id="is_new" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                            <span class="ml-2">Mark as New</span>
                        </label>
                        @error('is_new')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_on_sale" id="is_on_sale" {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}>
                            <span class="ml-2">On Sale</span>
                        </label>
                        @error('is_on_sale')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-glow">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>