<?php
declare(strict_types=1);
namespace App\Blockchain\Consensus;

/**
 * @development
 *
 * This file is under development. The implementation may change in future versions.
 */

use App\Blockchain\Exceptions\Consensus\SlashingException;
use App\Blockchain\Interfaces\Consensus\SlashableInterface;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\SuspensionBlocks;

/**
 * Class SlashingMechanism
 *
 * Universal slashing mechanism for various blockchain consensus systems.
 * Can reduce stake, suspend, or completely remove actors (delegates, validators, stakers).
 */
class SlashingMechanism
{
    public function __construct(
        private float $slashingPercentage = 0.05
    ) {
        $this->setSlashingPercentage($slashingPercentage);
    }

    public function setSlashingPercentage(float $newPercentage): void
    {
        if ($newPercentage <= 0 || $newPercentage >= 1) {
            SlashingException::invalidSlashingPercentage();
        }
        $this->slashingPercentage = $newPercentage;
    }

    /**
     * Slash an entity (delegate, validator, staker) by reducing its staked tokens.
     *
     * @param SlashableInterface $entity The entity to be slashed.
     * @throws SlashingException If slashing fails.
     */
    public function slash(SlashableInterface $entity): void
    {
        $slashAmount = (int)($entity->getStake()->value() * $this->slashingPercentage);

        if ($slashAmount <= 0) {
            throw SlashingException::InvalidSlashingAmount();
        }

        $entity->reduceStake($slashAmount);

        if ($entity->getStake()->value() <= 0) {
            throw SlashingException::EntityBankrupt($entity->getId()->value());
        }

        $this->notifyOfSlashing($entity, $slashAmount);
    }

    /**
     * Optionally notify about slashing.
     *
     * @param SlashableInterface $entity
     * @param int $slashAmount
     */
    private function notifyOfSlashing(SlashableInterface $entity, int $slashAmount): void
    {
        echo sprintf(
            "user %s has been slashed by %d tokens.",
            $entity->getId()->value(),
            $slashAmount
        );
    }

    /**
     * Optionally suspend the entity for a given number of blocks or time period.
     *
     * @param SlashableInterface $entity
     * @param SuspensionBlocks $blocks Number of blocks to suspend the entity.
     */
    public function suspend(SlashableInterface $entity, SuspensionBlocks $blocks): void
    {
        $entity->suspend($blocks);
        echo sprintf("user %s has been suspended for %d blocks.", $entity->getId()->value(), $blocks->value());
    }

    /**
     * Unfreezes the specified entity, allowing it to participate in the consensus process again.
     *
     * @param SlashableInterface $entity The entity to be unfrozen.
     */
    public function unfreeze(SlashableInterface $entity): void
    {
        $entity->unfreeze();
        echo sprintf("user %s has been unfrozen.", $entity->getId()->value());
    }
}
