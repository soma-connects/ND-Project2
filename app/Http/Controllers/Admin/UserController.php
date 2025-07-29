<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('carts')->orderBy('created_at', 'desc')->paginate(10);
        Log::info('Admin users loaded', ['user_count' => $users->count()]);
        return view('admin.users.index', compact('users'));
    }
}