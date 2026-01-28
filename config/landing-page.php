<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Active Landing Page
    |--------------------------------------------------------------------------
    |
    | Tentukan landing page mana yang aktif saat ini.
    | Nilai yang valid: 'dindik', 'disnaker'
    |
    */
    'active' => env('ACTIVE_LANDING_PAGE', 'dindik'),

    /*
    |--------------------------------------------------------------------------
    | Available Landing Pages
    |--------------------------------------------------------------------------
    |
    | Daftar landing page yang tersedia beserta konfigurasinya
    |
    */
    'pages' => [
        'dindik' => [
            'name' => 'Dinas Pendidikan',
            'slug' => 'dindik',
            'provider' => \Paparee\BaleDindik\BaleDindikServiceProvider::class,
            'enabled' => true,
        ],
        'disnaker' => [
            'name' => 'Dinas Tenaga Kerja',
            'slug' => 'disnaker',
            'provider' => \Paparee\BaleDisnaker\BaleDisnakerServiceProvider::class,
            'enabled' => true,
        ],
    ],
];
