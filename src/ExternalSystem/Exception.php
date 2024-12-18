<?php

declare(strict_types=1);

namespace App\ExternalSystem;

class Exception extends \Exception
{
    public function __construct(string $message)
    {
        $message = sprintf('External system error: %s', $message);
        parent::__construct($message);
    }
}
