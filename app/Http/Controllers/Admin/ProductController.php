<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        Log::info('Product store request received', ['input' => $request->all()]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:shroom,cap,sheet,pet_food,flowers',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'is_new' => 'nullable|boolean',
            'is_on_sale' => 'nullable|boolean',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Store the image and get the full path
                $imagePath = $request->file('image')->store('products', 'public');
                // CORRECTED: Save the full path returned by store(), not the basename
                $validated['image'] = $imagePath;
                Log::info('Image stored', ['path' => $imagePath]);
            }

            $validated['is_new'] = $request->has('is_new');
            $validated['is_on_sale'] = $request->has('is_on_sale');
            
            Product::create($validated);
            Log::info('Product created successfully.');

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            Log::error('Product creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        Log::info('Product update request', ['id' => $product->id, 'input' => $request->all()]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:shroom,cap,sheet,pet_food,flowers',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'is_new' => 'nullable|boolean',
            'is_on_sale' => 'nullable|boolean',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                    Log::info('Old image deleted', ['path' => $product->image]);
                }
                // Store the new image
                $imagePath = $request->file('image')->store('products', 'public');
                // CORRECTED: Save the full path
                $validated['image'] = $imagePath;
                Log::info('New image stored', ['path' => $imagePath]);
            }

            $validated['is_new'] = $request->has('is_new');
            $validated['is_on_sale'] = $request->has('is_on_sale');

            $product->update($validated);
            Log::info('Product updated successfully', $product->toArray());

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Product update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // CORRECTED: Use the full path from the database to delete the image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                Log::info('Image deleted', ['path' => $product->image]);
            }
            $product->delete();
            Log::info('Product deleted', ['id' => $product->id]);

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Product deletion failed', ['message' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
