<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $payment->client_id = $paymentIntent->client_secret;

        $payment->save();

        return [
            "client_id" => $paymentIntent->client_secret,
            "stripe_key" => config('stripe.stripe_public_key')
        ];
    }
    public function confirm(Request $request, string $client_id ): Response{
        StripeLogger::setApiKey(config('stripe.stripe_secret_key'));
        $payment = Payment::where("client_id", $client_id)->first();
        // dd($request, $client_id);
        $paymentIntent = PaymentIntent::retrieve($payment->stripe_id);

        if ($paymentIntent->status === "succeeded") {
            $payment->payment_state = "payé";
        }
        else {
            $payment->payment_state = "échoué";

        }
        $payment->client_id = null;
        $payment->save();
        return response()->noContent();
    }
}