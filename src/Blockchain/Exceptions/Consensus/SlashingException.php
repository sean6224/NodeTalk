<?php
declare(strict_types=1);

namespace App\Blockchain\Exceptions\Consensus;

use RuntimeException;

/**
 * Class SlashingException
 *
 * Exception thrown during slashing events in the blockchain system.
 */
class SlashingException extends RuntimeException
{
    /**
     * Exception message when the slashing amount is invalid.
     *
     * @return SlashingException
     */
    public static function invalidSlashingPercentage(): self
    {
        return new self('Slashing percentage must be between 0 and 1');
    }

    /**
     * Exception message when the slashing amount is invalid.
     *
     * @return SlashingException
     */
    public static function invalidSlashingAmount(): self
    {
        return new self('The amount to be slashed is invalid or too low.');
    }

    /**
     * Exception message when the entity is bankrupt after slashing.
     *
     * @param string $entityId The ID of the entity that went bankrupt.
     * @return SlashingException
     */
    public static function entityBankrupt(string $entityId): self
    {
        return new self(sprintf('Entity with ID %s has become bankrupt after slashing.', $entityId));
    }

    /**
     * Exception message when a delegate is slashed.
     *
     * @param string $delegateId
     * @return SlashingException
     */
    public static function delegateSlashed(string $delegateId): self
    {
        return new self(sprintf('Delegate with ID %s has been slashed.', $delegateId));
    }

    /**
     * Exception message when a delegate is inactive.
     *
     * @param string $delegateId
     * @return SlashingException
     */
    public static function delegateInactive(string $delegateId): self
    {
        return new self(sprintf('Delegate with ID %s is inactive and cannot be slashed.', $delegateId));
    }

    /**
     * Exception message for invalid slashing operation.
     *
     * @param string $message
     * @return SlashingException
     */
    public static function invalidSlashingOperation(string $message): self
    {
        return new self(sprintf('Invalid slashing operation: %s', $message));
    }
}
