<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'فروشگاه من'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'Asia/Tehran',
    'locale' => env('APP_LOCALE', 'fa'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'fa_IR'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => array_filter(
        explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
    ),

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // Laravel's core providers must be loaded here. In particular, the
    // Filesystem and Session providers register the services used by the app.
    'providers' => ServiceProvider::defaultProviders()
        ->merge([
            // Package Service Providers...
        ])
        ->merge([
            // Application Service Providers...
        ])
        ->merge([
            // Added Service Providers (Do not remove this line)...
        ])
        ->toArray(),

    'aliases' => Facade::defaultAliases()
        ->merge([
            // Application aliases...
        ])
        ->toArray(),
];
