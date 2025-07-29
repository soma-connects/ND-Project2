<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        Log::info('Admin carts index loaded');
        $users = User::with(['carts.product'])->whereHas('carts')->get();
        return view('admin.carts.index', compact('users'));
    }
}