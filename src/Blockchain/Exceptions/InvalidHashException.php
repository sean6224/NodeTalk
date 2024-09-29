<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions;

use InvalidArgumentException;

class InvalidHashException extends InvalidArgumentException
{
    public function __construct(string $hash)
    {
        parent::__construct("Invalid transaction hash: $hash");
    }
}
