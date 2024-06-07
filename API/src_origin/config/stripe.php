<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application stripe config
    |--------------------------------------------------------------------------
    |
    | Those two key define how to connect with stripe client
    |
    */
    'stripe_secret_key' => env('STRIPE_PRIVATE_KEY', ''),
    'stripe_public_key' => env('STRPIE_PUBLIC_KEY', '')
];