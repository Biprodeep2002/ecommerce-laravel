<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\Product;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // if (Auth::attempt($credentials)) {
        //     return redirect()->route('home');
        // }
        // \Log::info('User in controller: ', ['user' => auth()->user()]);
        //     return view('products.index', [
        //         'user' => auth()->user(),
        //         'products' => Product::all()
        // ]);
        if (Auth::attempt($credentials)) {
            // Optional: redirect admin and user differently
            if (Auth::user()->role === 'admin') {
                //return redirect()->route('admin.orders')->with('success', 'Welcome, Admin!');
                return redirect()->route('home')->with('success', 'Welcome, Admin!');
            } else {
                return redirect()->route('home')->with('success', 'Login successful.');
            }
        }

        return back()->withErrors(['login_error' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}
