<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Storage::disk('public')->files('assets/img');
        $images = array_map('basename', $images);
        return view('admin.images.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('assets/img', 'public');
        return redirect()->route('admin.images.index')->with('success', 'Image uploaded successfully.');
    }

    public function destroy($image)
    {
        if (Storage::disk('public')->exists('assets/img/' . $image)) {
            Storage::disk('public')->delete('assets/img/' . $image);
            return redirect()->route('admin.images.index')->with('success', 'Image deleted successfully.');
        }
        return redirect()->route('admin.images.index')->with('error', 'Image not found.');
    }
}