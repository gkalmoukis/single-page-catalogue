<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Admin Password
    |--------------------------------------------------------------------------
    |
    | This value is used as the default password for the admin user when
    | seeding the database. Change this value in production environments.
    |
    */
    'admin_password' => env('DEFAULT_ADMIN_PASSWORD', 'password'),

    /*
    |--------------------------------------------------------------------------
    | Catalogue Cache Key
    |--------------------------------------------------------------------------
    |
    | This value is used as the cache key for storing the restaurant catalogue
    | data including categories and items. Change this if you need to invalidate
    | the cache or use different cache keys for different environments.
    |
    */
    'catalogue_cache_key' => env('CATALOGUE_CACHE_KEY', 'catalogue'),
];