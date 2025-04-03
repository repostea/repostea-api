<?php

return [
    'promote_links_enabled' => env('ENABLE_PROMOTE_LINKS', false),
    'update_karma_enabled' => env('ENABLE_UPDATE_KARMA', false),
    'daily_reset_enabled' => env('ENABLE_DAILY_DB_RESET', false),
    'daily_reset_confirmed' => env('CONFIRM_DAILY_DB_RESET', ''),
];
