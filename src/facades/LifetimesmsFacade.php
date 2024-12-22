<?php

namespace Lifetimesms\Gateway\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed sendMessage(string $phoneNumber, string $message)
 * @method static mixed checkBalance()
 * @method static mixed sendBulkMessages(array $phoneNumbers, string $message)
 */
class LifetimesmsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lifetimesms';
    }
}
