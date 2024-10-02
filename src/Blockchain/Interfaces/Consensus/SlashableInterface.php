<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Consensus;

use App\Blockchain\ValueObject\Blockchain\SuspensionBlocks;

/**
 * Interface SlashableInterface
 *
 * Defines the behavior of entities that can be subjected to slashing in a blockchain system.
 * It provides methods for managing suspension blocks, checking suspension status, and restoring entities.
 */
interface SlashableInterface
{
    public function suspend(SuspensionBlocks $blocks): void;

    public function unfreeze(): void;

    public function isSuspended(): bool;
}
