<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions\Consensus;

use Exception;

/**
 * Exception related to Proof of StakeholderStake operations.
 */
class ProofOfStakeException extends Exception
{
    public static function MaxDelegatesExceeded(): self
    {
        return new self("Maximum number of delegates reached.");
    }

    public static function StakeholderNotFound(string $id): self
    {
        return new self("Stakeholder with ID '$id' not found in repository.");
    }

    public static function NoDelegatesAvailable(): self
    {
        return new self("No delegates available.");
    }

    public static function DelegateNotFound(): self
    {
        return new self("Delegate not found.");
    }

    public static function NoActiveDelegatesAvailable(): self
    {
        return new self("No active delegates available.");
    }
}
