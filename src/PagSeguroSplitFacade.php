<?php

namespace Aireset\PagSeguro;

use Illuminate\Support\Facades\Facade;

class PagSeguroSplitFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pagseguro_split';
    }
}
