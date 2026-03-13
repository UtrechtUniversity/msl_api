<?php

return [
    'enabled' => env('TRACKING_ENABLED', false),
    'host' => env('MATOMO_HOST', ''),
    'site_id' => env('MATOMO_SITE_ID', ''),
    'token' => env('MATOMO_TOKEN', ''),
];
