<?php

return [
    'balystics_id' => env('BALYSTICS_ID'),

    /*
    |--------------------------------------------------------------------------
    | CDN Configuration
    |--------------------------------------------------------------------------
    */
    'cdn' => [
        // Enable / disable CDN
        'enabled' => env('EMPERAN_CDN_ENABLED', true),

        // CDN base URL (tanpa trailing slash)
        // contoh: https://cdn.bale.co.id
        'base_url' => env('EMPERAN_CDN_URL'),

        // Prefix path di CDN
        // contoh: bale
        'prefix' => env('EMPERAN_CDN_PREFIX', 'bale'),
    ],
];
