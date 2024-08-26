<?php

namespace App\Exceptions;

use Exception;

class InvalidExchangeRewardException extends Exception
{
    protected $reason;

    public function __construct(string $message, string $reason, int $code = 0)
    {
        $this->reason = $reason;

        parent::__construct($message, $code);
    }

    public function getReason()
    {
        return $this->reason;
    }
}
