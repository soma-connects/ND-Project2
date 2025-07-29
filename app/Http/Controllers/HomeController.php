<?php
namespace App\Http\Controllers;

use App\Mail\ContactForm;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    private function getProducts(Request $request, $category = null)
    {
        $query = Product::query();
        if ($category) {
            $query->where('category', $category);
        }
        return $query->when($request->input('sort'), function ($query, $sort) {
            if ($sort === 'price-asc') {
                return $query->orderBy('price', 'asc');
            } elseif ($sort === 'price-desc') {
                return $query->orderBy('price', 'desc');
            } elseif ($sort === 'name-asc') {
                return $query->orderBy('name', 'asc');
            } elseif ($sort === 'newest') {
                return $query->orderBy('created_at', 'desc');
            }
        })->paginate(8);
    }

    public function index()
    {
        $products = Product::take(6)->get();
        session()->forget('success');
        return view('home.index', compact('products'));
    }

    public function shop(Request $request)
    {
        $products = $this->getProducts($request);
        return view('product.shop', compact('products'));
    }

    public function cap()
    {
        $products = $this->getProducts(request(), 'cap');
        return view('caps', [
            'title' => 'Capsules',
            'bodyclass' => 'cap',
            'products' => $products,
        ]);
    }

    public function sheet()
    {
        $products = $this->getProducts(request(), 'sheet');
        return view('sheet', [
            'title' => 'Sheets',
            'bodyclass' => 'sheet',
            'products' => $products,
        ]);
    }

    public function shroom()
    {
        $products = $this->getProducts(request(), 'shroom');
        return view('shroom', [
            'title' => 'Shrooms',
            'bodyclass' => 'shroom',
            'products' => $products,
        ]);
    }

    public function product($id)
{
    $product = Product::findOrFail($id);
    $recommendedProducts = Product::where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->inRandomOrder() // Ensures random selection
        ->take(4)
        ->get();
    return view('product.show', [
        'title' => $product->name,
        'bodyclass' => 'product',
        'product' => $product,
        'recommendedProducts' => $recommendedProducts,
    ]);
}

    public function aboutus(Request $request)
    {
        return view('about-us');
    }

    public function contactus(Request $request)
    {
        return view('contact-us');
    }
   public function contactSubmit(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string',
    ]);

    try {
        Mail::to('hello@pawspetalsfungi.com')->send(new ContactForm($validated));
        return redirect()->route('contactus')->with('success', 'Your message has been sent successfully!');
    } catch (\Exception $e) {
        return redirect()->route('contactus')->with('error', 'Failed to send message. Please try again later.');
    }
}

    public function cart(Request $request)
    {
        $cartItems = session()->get('cart.items', []);
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));
        $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;
        return view('cart', compact('cartItems', 'subtotal', 'total'));
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        // Save email to newsletter list
        return redirect()->back()->with('success', 'Subscribed successfully!');
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => asset('storage/' . $product->image),
            'description' => $product->description,
            'sku' => $product->sku,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
        ]);
    }

   public function search(Request $request)
{
    $query = $request->input('q');
    $products = Product::where('name', 'like', "%$query%")
        ->orWhere('description', 'like', "%$query%")
        ->when($request->input('sort'), function ($query, $sort) {
            if ($sort === 'price-asc') {
                return $query->orderBy('price', 'asc');
            } elseif ($sort === 'price-desc') {
                return $query->orderBy('price', 'desc');
            } elseif ($sort === 'name-asc') {
                return $query->orderBy('name', 'asc');
            } elseif ($sort === 'newest') {
                return $query->orderBy('created_at', 'desc');
            }
        })->paginate(9);
    return view('search', compact('products', 'query'));
}
}