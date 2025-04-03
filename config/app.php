<?php

return [
    'site_name' => env('REPOSTEA_SITE_NAME', 'Repostea'),
    'site_description' => env('REPOSTEA_SITE_DESCRIPTION', 'Una ventana abierta a lo que se comparte en la red'),

    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Europe/Madrid'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [...array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    'items_per_page' => env('REPOSTEA_ITEMS_PER_PAGE', 25),

    'repostea' => [
        'initial_karma' => env('REPOSTEA_INITIAL_KARMA', 6.0),
        'min_karma_for_negative_vote' => env('REPOSTEA_MIN_KARMA_FOR_NEGATIVE_VOTE', 8.0),
        'min_karma_for_promotion' => env('REPOSTEA_MIN_KARMA_FOR_PROMOTION', 20),
        'min_hours_for_promotion' => env('REPOSTEA_PROMOTION_TIME_HOURS', 6),
        'min_karma_for_comment' => env('REPOSTEA_MIN_KARMA_FOR_COMMENT', 5.0),
        'max_links_per_day' => env('REPOSTEA_MAX_LINKS_PER_DAY', 10),
        'max_links_in_row' => env('REPOSTEA_MAX_LINKS_IN_ROW', 3),
        'time_between_submissions' => env('REPOSTEA_TIME_BETWEEN_SUBMISSIONS', 900),
        'time_between_comments' => env('REPOSTEA_TIME_BETWEEN_COMMENTS', 30),
        'banned_domains' => [],
        'banned_words' => [],
        'allow_nsfw' => env('REPOSTEA_ALLOW_NSFW', true),

    ],
];
