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

        return [];
    }
}