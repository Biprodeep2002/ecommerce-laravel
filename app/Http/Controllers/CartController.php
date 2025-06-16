<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return view('cart.view', compact('cart', 'total'));
    }

    public function showProductSelector()
    {
        $products = Product::with('category', 'physicalData', 'digitalData')->get();
        return view('cart.select', compact('products'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.select')->with('success', 'Product added to cart successfully.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('home')->with('success', 'Cart cleared successfully.');
    }
}

