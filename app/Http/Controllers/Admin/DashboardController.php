<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentReceipt;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('Admin dashboard loaded');

        // Fetch summary stats
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalUsers = User::count();
        $recentPayments = PaymentReceipt::with('order.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent items
        $recentProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $recentCarts = User::has('carts')->with(['carts' => function ($query) {
            $query->with('product');
        }])->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'pendingOrders',
            'totalUsers',
            'recentPayments',
            'recentProducts',
            'recentOrders',
            'recentCarts'
        ));
    }
}