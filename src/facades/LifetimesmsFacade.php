<?php

namespace Lifetimesms\Gateway\Facades;

use Illuminate\Support\Facades\Facade;

class LifetimesmsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lifetimesms';
    }
}