<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backend Routes Prefix
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify a global routes prefix for the
    | backend that can be used in any other module.
    |
    */

    'backend_routes_prefix' => env('BACKEND_ROUTES_PREFIX', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Frontend Routes Prefix
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify a global routes prefix for the
    | frontend that can be used in any other module.
    |
    */

    'frontend_routes_prefix' => env('FRONTEND_ROUTES_PREFIX', 'app'),

    /*
    |--------------------------------------------------------------------------
    | User Registration
    |--------------------------------------------------------------------------
    |
    | Enable or disable public user registration.
    |
    */

    'user_registration' => env('USER_REGISTRATION', false),

    /*
    |--------------------------------------------------------------------------
    | Authentication Image
    |--------------------------------------------------------------------------
    |
    | Here you can change the default image displayed in authentication views.
    |
    */

    'auth_image' => env('AUTH_IMAGE', 'https://source.unsplash.com/featured/640x480/?nature'),

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the application's logo by specifying a path relative
    | to the public folder.
    |
    */

    'logo' => env('LOGO', false),

    /*
    |--------------------------------------------------------------------------
    | Copyright Info
    |--------------------------------------------------------------------------
    |
    | Here you can change the copyright info used in the application's footer.
    |
    */

    'copyright_link' => env('COPYRIGHT_LINK', 'https://github.com/systeady'),
    'copyright_name' => env('COPYRIGHT_NAME', 'SySteady'),

];
