<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Stripe\PaymentIntent;
use Stripe\Stripe as StripeLogger;

class PaymentController extends Controller
{
    public function create(Request $request, Order $order) {
        StripeLogger::setApiKey(config('stripe.stripe_secret_key'));

        $totalAmount = 100* $order->total_price;
        // $totalAmount = (int) $totalAmount;

        $paymentIntent = PaymentIntent::create([
            'amount' => (int) $totalAmount,
            'currency' => 'EUR',
        ]);

        $payment = new Payment();
        $payment->stripe_id = $paymentIntent->id;
        $payment->payment_state = "waiting";
        $payment->order_id = $order->id;

        $payment->save();

        return [
            "client_id" => $paymentIntent->client_secret,
            "stripe_key" => config('stripe.stripe_public_key')
        ];
    }
}