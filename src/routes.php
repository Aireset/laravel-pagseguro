<?php

    Route::group(['namespace' => 'Aireset\PagSeguro'], function () {
        Route::get('/pagseguro/session', 'PagSeguroController@session');
        Route::get('/pagseguro/javascript', 'PagSeguroController@javascript');
    });
