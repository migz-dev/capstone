<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Supported: "session"
    */
    'guards' => [
        // Students / default app users
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Faculty (separate session; prevents clashes with students/admin)
        'faculty' => [
            'driver'   => 'session',
            'provider' => 'faculty',
        ],

        // Admin (optional — enable when your Admin model/guard is ready)
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Supported: "eloquent", "database"
    */
    'providers' => [
        // Students / default users
        'users' => [
            'driver' => 'eloquent',
            'model'  => env('AUTH_MODEL', App\Models\User::class),
        ],

        // Faculty provider (uses App\Models\Faculty)
        'faculty' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Faculty::class,
        ],

        // Admin provider (optional; point to your Admin model/table)
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class, // create this when ready
        ],

        // Example if you ever use DB provider:
        // 'users' => [
        //     'driver' => 'database',
        //     'table'  => 'users',
        // ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        // Default (students / users)
        'users' => [
            'provider' => 'users',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],

        // Faculty broker (enable if you add “Forgot password” for faculty)
        'faculty' => [
            'provider' => 'faculty',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],

        // Admin broker (optional)
        'admins' => [
            'provider' => 'admins',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    | Default: 3 hours
    */
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
