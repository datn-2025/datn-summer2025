<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GHN (Giao Hàng Nhanh) Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for GHN (Giao Hàng Nhanh) 
    | shipping service integration.
    |
    */

    'api_url' => env('GHN_API_URL', 'https://dev-online-gateway.ghn.vn/shiip/public-api'),
    'api_key' => env('GHN_API_KEY'),
    'shop_id' => env('GHN_SHOP_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Shop Default Address
    |--------------------------------------------------------------------------
    |
    | Default shop address for calculating shipping fees from
    |
    */
    'from_district_id' => env('GHN_FROM_DISTRICT_ID', 1454), // Quận Hai Bà Trưng, Hà Nội
    'from_ward_code' => env('GHN_FROM_WARD_CODE', '21211'), // Phường Bạch Mai
      /*
    |--------------------------------------------------------------------------
    | Default Package Dimensions
    |--------------------------------------------------------------------------
    |
    | Default dimensions for packages when not specified
    |
    */
    'default_weight' => 200, // grams
    'book_weight' => 150,    // grams per physical book
    'default_length' => 20,  // cm
    'default_width' => 15,   // cm
    'default_height' => 10,  // cm
    
    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache duration for API responses
    |
    */
    'cache_duration' => [
        'provinces' => 3600, // 1 hour
        'districts' => 3600, // 1 hour
        'wards' => 3600,     // 1 hour
        'shipping_info' => 300, // 5 minutes
    ],
];
