<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (!$cart || count($cart) === 0) {
            return redirect()->route('cart.view')->with('error', 'Cart is empty.');
        }
        $request->validate(['email' => 'required|email']);
        session()->put('checkout_email', $request->input('email'));
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = array_map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] *100, // in paise
                ],
                'quantity' => $item['quantity'],
            ];
        }, array_values($cart)); //  This resets the keys to 0, 1, 2...

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('cart.view'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        $cart = session()->get('cart', []);
        if (!$cart) {
            return redirect()->route('home');
        }

        $userId = auth()->id();
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $userId,
            'total_amount' => $total,
            //'status' => 'paid',
            'created_at' => now(),
            //'updated_at' => now(),
        ]);

        foreach ($cart as $productId => $item) {
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        $email = session()->get('checkout_email');
        if (!$email) {
            return redirect()->route('home')->with('error', 'Email not found.');
        }
        $user = auth()->user();
        Mail::to($email)->send(new OrderConfirmationMail($user->username, $orderId, $cart, $total));
        session()->forget('checkout_email');
        session()->forget('cart');
        
        return view('checkout.success');
    }
}
