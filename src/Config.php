<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sandbox
    |--------------------------------------------------------------------------
    |
    | Checa se utilizará o Sandbox ou Production.
    |
    */
    'sandbox' => env('PAGSEGURO_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    |
    | Conta de email do Vendedor.
    |
    */
    'email' => env('PAGSEGURO_EMAIL', ''),

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | Token do Vendedor.
    |
    */
    'token' => env('PAGSEGURO_TOKEN', ''),


    /*
    |--------------------------------------------------------------------------
    | appId
    |--------------------------------------------------------------------------
    |
    | Conta de email do Vendedor.
    |
    */
    'appid' => env('PAGSEGURO_APP_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | appKey
    |--------------------------------------------------------------------------
    |
    | Token do Vendedor.
    |
    */
    'appkey' => env('PAGSEGURO_APP_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | NotificationURL
    |--------------------------------------------------------------------------
    |
    | URL de resposta para notificações do Pagseguro.
    |
    */
    'notificationURL' => env('PAGSEGURO_NOTIFICATION', ''),

];