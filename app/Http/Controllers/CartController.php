<?php
namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentReceipt;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;

            return view('cart', compact('cartItems', 'subtotal', 'total'));
        } catch (\Exception $e) {
            Log::error('Cart index error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cart')->with('error', 'Failed to load cart.');
        }
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $quantity = $validated['quantity'] ?? 1;

        try {
            $product = Product::findOrFail($request->product_id);
            if ($product->stock < $quantity) {
                return $this->errorResponse('Requested quantity exceeds available stock.', $request->expectsJson(), 400);
            }

            if (Auth::check()) {
                $cartItem = Cart::updateOrCreate(
                    ['user_id' => Auth::id(), 'product_id' => $request->product_id],
                    ['quantity' => DB::raw('quantity + ' . $quantity)]
                );
            } else {
                $cart = Session::get('cart', []);
                $productId = $request->product_id;
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
                ];
                Session::put('cart', $cart);
            }

            return $this->successResponse('Product added to cart.', $request->expectsJson(), [
                'cartCount' => $this->getCartCount(),
            ]);
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to add to cart.', $request->expectsJson());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Cart update request', ['product_id' => $id, 'request_data' => $request->all()]);

        $quantity = $request->input('quantity');
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $product = Product::findOrFail($id);
            if ($quantity > $product->stock) {
                return $this->errorResponse('Requested quantity exceeds available stock.', $request->expectsJson(), 400);
            }

            if (Auth::check()) {
                $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $id)->firstOrFail();
                $cartItem->update(['quantity' => $quantity]);
            } else {
                $cart = Session::get('cart', []);
                if (!isset($cart[$id])) {
                    return $this->errorResponse('Item not found in cart.', $request->expectsJson(), 404);
                }
                $cart[$id]['quantity'] = $quantity;
                Session::put('cart', $cart);
            }

            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully.',
                    'cartCount' => $this->getCartCount(),
                    'itemTotal' => $product->price * $quantity,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ], 200);
            }

            return redirect()->route('cart')->with('success', 'Cart updated successfully.');
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage(), ['product_id' => $id, 'trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update cart.', $request->expectsJson(), 500);
        }
    }

    public function remove($id)
    {
        try {
            if (Auth::check()) {
                $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $id)->first();
                if ($cartItem) {
                    $cartItem->delete();
                } else {
                    return $this->errorResponse('Item not found in cart.', request()->expectsJson(), 404);
                }
            } else {
                $cart = Session::get('cart', []);
                if (!isset($cart[$id])) {
                    return $this->errorResponse('Item not found in cart.', request()->expectsJson(), 404);
                }
                unset($cart[$id]);
                Session::put('cart', $cart);
            }

            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'cartCount' => $this->getCartCount(),
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);
            }

            return redirect()->route('cart')->with('success', 'Item removed from cart.');
        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to remove item.', request()->expectsJson());
        }
    }

    public function checkout()
    {
        try {
            $cartItems = $this->getCartItems();
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Your cart is empty or contains unavailable items.');
            }

            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;

            return view('checkout', compact('cartItems', 'subtotal', 'total'));
        } catch (\Exception $e) {
            Log::error('Cart checkout error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cart')->with('error', 'Failed to load checkout.');
        }
    }

    public function storeOrder(Request $request)
    {
        // Guest checkout is now allowed, no authentication check needed

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::check() ? Auth::id() : null;
            Log::info('Starting order creation', ['user_id' => $userId, 'is_guest' => !Auth::check()]);

            $cartItems = $this->getCartItems();
            if ($cartItems->isEmpty()) {
                Log::warning('Cart is empty or contains unavailable items');
                DB::rollBack();
                return $this->errorResponse('Your cart is empty or contains unavailable items.', $request->expectsJson());
            }

            foreach ($cartItems as $cartItem) {
                if ($cartItem->product->stock < $cartItem->quantity) {
                    Log::warning('Insufficient stock', [
                        'product' => $cartItem->product->name,
                        'stock' => $cartItem->product->stock,
                        'requested' => $cartItem->quantity,
                    ]);
                    DB::rollBack();
                    return $this->errorResponse("Insufficient stock for {$cartItem->product->name}. Available: {$cartItem->product->stock}.", $request->expectsJson());
                }
            }

            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;
            Log::info('Calculated totals', ['subtotal' => $subtotal, 'total' => $total]);

            if (!$request->hasFile('receipt') || !$request->file('receipt')->isValid()) {
                Log::error('Invalid or missing receipt file');
                DB::rollBack();
                return $this->errorResponse('Invalid or missing receipt file.', $request->expectsJson());
            }

            $receiptPath = $request->file('receipt')->store('payment_receipts', 'public');
            Log::info('Payment receipt uploaded', ['path' => $receiptPath]);

            // Create order data array
            $orderData = [
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => 'pending',
            ];

            // If guest order, add guest information
            if (!Auth::check()) {
                $orderData['guest_name'] = $request->name;
                $orderData['guest_email'] = $request->email;
                $orderData['guest_phone'] = $request->phone;
                $orderData['guest_address'] = $request->address;
            }

            $order = Order::create($orderData);
            Log::info('Order created', ['order_id' => $order->id]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
                $cartItem->product->decrement('stock', $cartItem->quantity);
                Log::info('Order item created', ['product_id' => $cartItem->product_id, 'quantity' => $cartItem->quantity]);
            }

            $receipt = PaymentReceipt::create([
                'order_id' => $order->id,
                'receipt_path' => $receiptPath,
                'status' => 'pending',
            ]);
            Log::info('Payment receipt created', ['receipt_id' => $receipt->id, 'path' => $receiptPath]);

            // Clear cart based on user type
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
                Log::info('Cart cleared for authenticated user', ['user_id' => Auth::id()]);
            } else {
                Session::forget('cart');
                Log::info('Session cart cleared for guest user');
            }

            $confirmationUrl = URL::signedRoute('order.confirmation', ['order' => $order->id]);

            DB::commit();
            Log::info('Order transaction committed', ['order_id' => $order->id]);

            $recipientEmail = Auth::check() ? Auth::user()->email : $request->email;
            if ($recipientEmail) {
                try {
                    Mail::to($recipientEmail)->send(new OrderConfirmationMail($order->fresh('items.product'), $confirmationUrl));
                    Log::info('Order confirmation email dispatched', ['order_id' => $order->id, 'email' => $recipientEmail]);
                } catch (\Throwable $mailException) {
                    Log::error('Order confirmation email failed: ' . $mailException->getMessage(), [
                        'order_id' => $order->id,
                        'email' => $recipientEmail,
                    ]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully. Awaiting payment verification.',
                    'order_id' => $order->id,
                    'confirmation_url' => $confirmationUrl,
                ]);
            }

            return redirect()->to($confirmationUrl)->with('success', 'Order placed successfully. Awaiting payment verification.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to place order: ' . $e->getMessage(), $request->expectsJson());
        }
    }

    public function confirmation(Request $request, Order $order)
    {
        if ($order->isGuestOrder()) {
            if (!$request->hasValidSignature()) {
                Log::warning('Invalid guest order confirmation attempt', ['order_id' => $order->id, 'ip' => $request->ip()]);
                abort(403, 'This confirmation link is no longer valid.');
            }
        } else {
            if (!Auth::check() || Auth::id() !== $order->user_id) {
                Log::warning('Unauthorized order confirmation access', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                ]);
                abort(403);
            }
        }

        try {
            $order->load('items.product');
            return view('confirmation', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order confirmation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cart')->with('error', 'Failed to load order confirmation.');
        }
    }

    public function count()
    {
        try {
            return response()->json(['count' => $this->getCartCount()]);
        } catch (\Exception $e) {
            Log::error('Cart count error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['count' => 0]);
        }
    }

    public function summary()
    {
        try {
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal >= 50 ? $subtotal : $subtotal + 5;
            return response()->json([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('Cart summary error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['subtotal' => 0, 'total' => 0]);
        }
    }

    protected function getCartItems()
    {
        try {
            if (Auth::check()) {
                return Cart::where('user_id', Auth::id())
                    ->with('product')
                    ->get()
                    ->filter(fn($item) => $item->product && $item->product->stock >= $item->quantity);
            }

            $cart = Session::get('cart', []);
            $cartItems = collect([]);
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $cartItems->push((object)[
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $product,
                    ]);
                } else {
                    unset($cart[$item['product_id']]);
                }
            }
            Session::put('cart', $cart);
            return $cartItems;
        } catch (\Exception $e) {
            Log::error('Get cart items error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return collect([]);
        }
    }

    protected function getCartCount()
    {
        try {
            $cartItems = $this->getCartItems();
            return $cartItems->sum('quantity') ?? 0;
        } catch (\Exception $e) {
            Log::error('Get cart count error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return 0;
        }
    }

    public static function getCartCountStatic()
    {
        try {
            if (Auth::check()) {
                return Cart::where('user_id', Auth::id())
                    ->with('product')
                    ->get()
                    ->filter(fn($item) => $item->product && $item->product->stock >= $item->quantity)
                    ->sum('quantity') ?? 0;
            }

            $cart = Session::get('cart', []);
            $count = 0;
            foreach ($cart as $productId => $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $count += $item['quantity'];
                } else {
                    unset($cart[$productId]);
                }
            }
            Session::put('cart', $cart);
            return $count;
        } catch (\Exception $e) {
            Log::error('Cart count static error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return 0;
        }
    }

    protected function successResponse($message, $isJson, $additionalData = [])
    {
        if ($isJson) {
            return response()->json(array_merge(['success' => true, 'message' => $message], $additionalData));
        }
        return redirect()->route('cart')->with('success', $message);
    }

    protected function errorResponse($message, $isJson, $status = 500)
    {
        if ($isJson) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }
        return redirect()->route('cart')->with('error', $message);
    }
}
