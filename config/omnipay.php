<?php

return [

    'default' => env('OMNIPAY_GATEWAY', 'paypal_rest'),

    'gateways' => [

        'paypal_rest' => [
            'driver'   => 'PayPal_Rest',
            'options'  => [
                'clientId'     => env('OMNIPAY_PAYPAL_CLIENT_ID'),
                'secret'       => env('OMNIPAY_PAYPAL_SECRET'),
                'testMode'     => env('OMNIPAY_PAYPAL_TEST', true), // true = sandbox
                'currency'     => env('OMNIPAY_PAYPAL_CURRENCY', 'USD'),
            ],
        ],

        // لو حبيتي تضيفي Gateway تاني بعدين بتضيفيه هنا
    ],

];
