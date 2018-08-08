<?php
/**
 * Created by IntelliJ IDEA.
 * User: isda
 * Date: 29/05/2018
 * Time: 4:15 PM
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Bearer
    |--------------------------------------------------------------------------
    |
    | Specify the Authorization Bearer
    |
    */
    'bearer' => env('JWT_AUTHORIZATION_BEARER', 'Bearer'),

    /*
    |--------------------------------------------------------------------------
    | Token Exclusivity Validation
    |--------------------------------------------------------------------------
    |
    | Whether or not to validate the exclusivity of token
    | by iss (issuer) domain
    |
    */
    'validate_exclusivity' => env('JWT_VALIDATE_EXCLUSIVITY', false),

    /*
    |--------------------------------------------------------------------------
    | JWT Encryption Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this, as it will be used to encrypt your token claims.
    | A helper command is provided for this: `php artisan jwt:generate`
    |
    */

    'encrypt_secret' => env('JWT_ENCRYPT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Signature Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this, as it will be used to sign your tokens.
    | A helper command is provided for this: `php artisan jwt:generate`
    |
    */
    'signature_secret' => env('JWT_SIGNATURE_SECRET'),


    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 1 hour
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 2 week window of
    | the original token being created until they must re-authenticate.
    | Defaults to 2 weeks
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | JWT encrypt hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to encrypt the token.
    |
    */

    'encrypt_algo' => env("JWT_ENCRYPT_ALGO", 'A256GCM'),

    /*
    |--------------------------------------------------------------------------
    | JWT signature hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    */
    'signature_algo' => env("JWT_SIGNATURE_ALGO", 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | Blacklist Enabled
    |--------------------------------------------------------------------------
    |
    | In order to invalidate tokens, you must have the blacklist enabled.
    | If you do not want or need this functionality, then set this to false.
    |
    */

    'enable_blacklisting' => env('JWT_ENABLED_BLACKLISTING', true),


    'adapters' => [

        /*
        |--------------------------------------------------------------------------
        | Storage Implementation
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to store tokens in the blacklist
        |
        */

        'storage' => \Snp\JWT\Infra\Storage\IlluminateStorage::class,

        /*
        |--------------------------------------------------------------------------
        | Config Implementation
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to store config
        |
        */
        'config' => \Snp\JWT\Infra\Config\IlluminateConfig::class,

        /*
        |--------------------------------------------------------------------------
        | JOSE ADAPTER
        |--------------------------------------------------------------------------
        |
        | Specify which JOSE Adapter to be used
        |
        */
        'jose' => \Snp\JWT\Encryption\Adapters\SpomkyJWS::class,

    ],

    'grace_period' => [

        /*
        |--------------------------------------------------------------------------
        | NBF Grace Period
        |--------------------------------------------------------------------------
        |
        | Set Grace Period to avoid small time difference between servers
        | Value should be in seconds
        |
        */
        'nbf' => env('JWT_NOT_BEFORE_GRACE_PERIOD', 5),

        /*
        |--------------------------------------------------------------------------
        | Blacklist Grace Period
        |--------------------------------------------------------------------------
        |
        | Set Grace Period to still allow token even if blacklisted already
        | Value should be in seconds
        |
        */
        'blacklist' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    ]

];