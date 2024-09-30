<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Consensus;

use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderId;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\SuspensionBlocks;

/**
 * Interface SlashableInterface
 *
 * Defines the behavior of entities that can be subjected to slashing in a blockchain system.
 * It enables management of their stake, suspension of their activities, and their restoration to the system.
 */
interface SlashableInterface
{
    public function getId(): StakeholderId;
    public function getStake(): StakeholderStake;
    public function reduceStake(int $amount): void;
    public function suspend(SuspensionBlocks $blocks): void;

    public function unfreeze(): void;
}
