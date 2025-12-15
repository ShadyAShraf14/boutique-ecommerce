<?php

use BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider;

return [

    'apps' => [
        'provider' => ConfigAppProvider::class,
        'apps' => [
            [
                'id' => env('PUSHER_APP_ID'),          // = 1
                'name' => env('APP_NAME', 'Laravel'),
                'key' => env('PUSHER_APP_KEY'),       // local-websocket-key
                'secret' => env('PUSHER_APP_SECRET'), // local-websocket-secret
                'path' => env('PUSHER_APP_PATH'),
                'capacity' => null,
                'enable_client_messages' => false,
                'enable_statistics' => true,
            ],
        ],
    ],

    'allowed_origins' => [
        '*',
    ],

];
