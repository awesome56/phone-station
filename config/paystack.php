<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paystack API
    |--------------------------------------------------------------------------
    |
    | Secret key is used for server-to-server calls (initialize, verify).
    | Public key is exposed to the browser for the Paystack inline script.
    |
    */

    'secret_key' => env('PAYSTACK_SECRET_KEY'),

    'public_key' => env('PAYSTACK_PUBLIC_KEY'),

    'currency' => env('PAYSTACK_CURRENCY', 'NGN'),

    /*
    |--------------------------------------------------------------------------
    | Base URLs
    |--------------------------------------------------------------------------
    |
    */

    'api_url' => 'https://api.paystack.co',

    'payment_url' => 'https://paystack.com/pay',

    'webhook_url' => env('PAYSTACK_WEBHOOK_URL', '/webhooks/paystack'),

];
