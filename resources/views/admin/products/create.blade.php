<x-base-layout title="Create Product" bodyclass="admin-products-create">
    <div class="container">
        <div class="admin-form max-w-3xl mx-auto">
            <h1 class="font-playfair text-4xl font-bold mb-8 text-accent text-center">Create New Product</h1>
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="close">×</button>
                </div>
            @endif
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="form-control" required>
                        @error('sku')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" class="form-control" required>
                        @error('price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock') }}" class="form-control" required>
                        @error('stock')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="shroom" {{ old('category') == 'shroom' ? 'selected' : '' }}>Shrooms</option>
                        <option value="cap" {{ old('category') == 'cap' ? 'selected' : '' }}>Capsules</option>
                        <option value="sheet" {{ old('category') == 'sheet' ? 'selected' : '' }}>Sheets</option>
                        {{-- <option value="pet_food" {{ old('category') == 'pet_food' ? 'selected' : '' }}>Pet Food</option> --}}
                        {{-- <option value="flowers" {{ old('category') == 'flowers' ? 'selected' : '' }}>Flowers</option> --}}
                    </select>
                    @error('category')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" class="form-control">
                    @error('image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="6" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                            <span class="ml-2">Is New</span>
                        </label>
                        @error('is_new')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale') ? 'checked' : '' }}>
                            <span class="ml-2">Is On Sale</span>
                        </label>
                        @error('is_on_sale')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-glow">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</x-base-layout>