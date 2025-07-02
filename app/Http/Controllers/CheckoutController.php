<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Models\Order;

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

        $order = new Order();
        $order->user_id = $userId;
        $order->total_amount = $total;
        $order->save();

        foreach ($cart as $productId => $item) {
            $order->orderItems()->create([
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
        Mail::to($email)->send(new OrderConfirmationMail($user->username, $order->id, $cart, $total));
        session()->forget('checkout_email');
        session()->forget('cart');
        
        return view('checkout.success');
    }
}
