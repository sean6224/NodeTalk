<?php
declare(strict_types=1);
namespace App\Blockchain\ValueObject\Blockchain\Stakeholders;

use App\Shared\ValueObject\IntegerValue;
use LogicException;

final class StakeholderStake extends IntegerValue
{
    public function reduce(int $amount): void
    {
        $newValue = $this->value() - $amount;

        if ($newValue < 0) {
            throw new LogicException("Stake cannot be reduced below zero.");
        }

        $this->value = $newValue;
    }
}
