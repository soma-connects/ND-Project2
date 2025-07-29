<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function index()
    {
        $users = User::whereHas('carts')->with('carts.product')->get();
        $guestCarts = Cart::whereNull('user_id')->get()->groupBy('session_id');
        return view('admin.user-carts.index', compact('users', 'guestCarts'));
    }

    public function show(User $user)
    {
        $user->load('carts.product');
        return view('admin.user-carts.show', compact('user'));
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('admin.user-carts.index')->with('success', 'Cart item removed successfully.');
    }
}