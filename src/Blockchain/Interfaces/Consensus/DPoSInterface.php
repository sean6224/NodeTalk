<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Consensus;

use App\Blockchain\Consensus\Stakeholders\Delegate;
use App\Blockchain\Consensus\Stakeholders\Stakeholder;
use Exception;

/**
 * Interface DPoSInterface
 *
 * Provides methods for managing and interacting with delegates in a Delegated Proof of StakeholderStake (DPoS) system.
 * This interface defines how to add, remove, and select delegates based on their stake in the network.
 *
 * @package App\Blockchain\Interfaces\Consensus
 */
interface DPoSInterface
{
    /**
     * Adds a stakeholder as a delegate if they are selected by voting.
     *
     * @param Stakeholder $stakeholder
     * @throws Exception
     */
    public function voteForDelegate(Stakeholder $stakeholder): void;

    /**
     * Returns the list of current delegates.
     *
     * @return Delegate[]
     */
    public function getDelegates(): array;

    /**
     * Removes a delegate by their ID.
     *
     * @param string $id
     * @throws Exception
     */
    public function removeDelegateById(string $id): void;

    /**
     * Selects a delegate to validate the block (weighted random selection).
     *
     * @return Delegate
     *
     */
    public function selectDelegateForBlock(): Delegate;

}
