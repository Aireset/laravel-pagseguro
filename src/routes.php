<?php

    Route::group(['namespace' => 'Zhiru\PagSeguro'], function () {
        Route::get('/pagseguro/session', 'PagSeguroController@session');
        Route::get('/pagseguro/javascript', 'PagSeguroController@javascript');
    });
