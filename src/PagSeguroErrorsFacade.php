<?php
    
    namespace Aireset\PagSeguro;
    
    use Illuminate\Support\Facades\Facade;
    
    class PagSeguroErrorsFacade extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return 'pagseguro_errors';
        }
    }
