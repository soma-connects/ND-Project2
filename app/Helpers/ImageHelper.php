<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the publicly accessible URL for a product image.
     *
     * @param string|null $imagePath The path to the image file as stored in the database.
     * @return string The full URL to the image or a placeholder.
     */
    public static function getProductImage($imagePath)
    {
        // Check if an image path is provided and if the file exists on the 'public' disk.
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            // Return the full public URL for the file.
            // This correctly generates a URL like http://your-app.com/storage/products/image.jpg
            return Storage::disk('public')->url($imagePath);
        }

        // If no valid image is found, return the path to a default placeholder image.
        // Make sure you have a 'placeholder.jpg' in your 'public/assets/img/' directory.
        return asset('assets/img/placeholder.jpg');
    }
}
