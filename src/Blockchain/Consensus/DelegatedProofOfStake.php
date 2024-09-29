<?php
declare(strict_types=1);
namespace App\Blockchain\Consensus;

use App\Blockchain\Consensus\Stakeholders\Delegate;
use App\Blockchain\Consensus\Stakeholders\Stakeholder;
use App\Blockchain\Exceptions\Consensus\ProofOfStakeException;
use App\Blockchain\Interfaces\Consensus\DPoSInterface;
use Random\RandomException;

/**
 * Class DPoS
 *
 * Implements the Delegated Proof of StakeholderStake (DPoS) consensus algorithm for blockchain.
 * Manages a list of delegates, tracks their stakes, and selects a delegate to validate blocks
 * based on a weighted random selection process.
 *
 * @package App\Blockchain\Consensus
 */
class DelegatedProofOfStake implements DPoSInterface
{
    private array $delegates = [];
    private int $maxDelegates = 21;
    private array $cumulativeStakes = [];
    private int $totalStake = 0;

    /**
     * Adds a stakeholder as a delegate if they are selected by voting.
     *
     * @param Stakeholder $stakeholder
     * @throws ProofOfStakeException
     */
    public function voteForDelegate(Stakeholder $stakeholder): void
    {
        if (count($this->delegates) >= $this->maxDelegates) {
            throw ProofOfStakeException::MaxDelegatesExceeded();
        }

        $delegate = new Delegate(
            $stakeholder->getId(),
            $stakeholder->getName(),
            $stakeholder->getStake()
        );
        $stakeholderId = $stakeholder->getId()->value();

        if (isset($this->delegates[$stakeholderId])) {
            $this->totalStake -= $this->delegates[$stakeholderId]->getStake();
        }

        $this->delegates[$stakeholderId] = $delegate;
        $this->totalStake += $delegate->getStake()->value();
        $this->updateCumulativeStakes();
    }

    /**
     * Updates the cumulative stakes.
     */
    private function updateCumulativeStakes(): void
    {
        $this->cumulativeStakes = [];
        $cumulativeStake = 0;

        foreach ($this->delegates as $delegate) {
            if ($delegate->isActive()) {
                $cumulativeStake += $delegate->getStake()->value();
                $this->cumulativeStakes[] = $cumulativeStake;
            }
        }
    }

    /**
     * Returns the list of current delegates.
     *
     * @return Delegate[]
     */
    public function getDelegates(): array
    {
        return $this->delegates;
    }

    /**
     * Removes a delegate by their ID.
     *
     * @param string $id
     * @throws ProofOfStakeException
     */
    public function removeDelegateById(string $id): void
    {
        if (!isset($this->delegates[$id])) {
            throw ProofOfStakeException::DelegateNotFound();
        }

        $delegate = $this->delegates[$id];
        $this->totalStake -= $delegate->getStake()->value();
        unset($this->delegates[$id]);
        $this->updateCumulativeStakes();
    }

    /**
     * Selects a delegate to validate the block (weighted random selection).
     *
     * @return Delegate
     * @throws ProofOfStakeException
     * @throws RandomException
     */
    public function selectDelegateForBlock(): Delegate
    {
        if ($this->totalStake === 0) {
            throw ProofOfStakeException::NoDelegatesAvailable();
        }

        if (empty($this->cumulativeStakes)) {
            throw ProofOfStakeException::NoActiveDelegatesAvailable();
        }

        $random = random_int(0, $this->totalStake - 1);

        foreach ($this->cumulativeStakes as $index => $cumulativeStake) {
            if ($random < $cumulativeStake) {
                $delegateId = array_keys($this->delegates)[$index];
                return $this->delegates[$delegateId];
            }
        }

        throw ProofOfStakeException::NoActiveDelegatesAvailable();
    }
}
