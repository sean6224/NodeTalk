<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Consensus;

use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;

/**
 * Interface StakeableInterface
 *
 * Defines the behavior of entities that have a stake in the blockchain system.
 */
interface StakeableInterface extends SlashableInterface
{
    public function getStake(): StakeholderStake;

    public function reduceStake(int $amount): void;
}
