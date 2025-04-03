<?php

return [
    'instance' => [
        'name' => env('ACTIVITYPUB_INSTANCE_NAME', config('app.name')),
        'domain' => parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST),
        'url' => config('app.url'),
        'description' => env('ACTIVITYPUB_INSTANCE_DESCRIPTION', 'Una ventana abierta a lo que se comparte en la red'),
        'version' => '1.0.0',
        'email' => env('ACTIVITYPUB_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS')),
    ],

    'keys' => [
        'private' => storage_path('app/private/activitypub/private.pem'),
        'public' => storage_path('app/private/activitypub/public.pem'),
    ],

    'federation' => [
        'enabled' => env('ACTIVITYPUB_FEDERATION_ENABLED', false),
        'limited_federation' => env('ACTIVITYPUB_LIMITED_FEDERATION', false),
        'allowed_domains' => explode(',', env('ACTIVITYPUB_ALLOWED_DOMAINS', '')),
        'blocked_domains' => explode(',', env('ACTIVITYPUB_BLOCKED_DOMAINS', '')),
    ],

    'limits' => [
        'max_followers' => env('ACTIVITYPUB_MAX_FOLLOWERS', 5000),
        'max_following' => env('ACTIVITYPUB_MAX_FOLLOWING', 5000),
        'delivery_timeout' => env('ACTIVITYPUB_DELIVERY_TIMEOUT', 10),
        'delivery_attempts' => env('ACTIVITYPUB_DELIVERY_ATTEMPTS', 3),
    ],
];
