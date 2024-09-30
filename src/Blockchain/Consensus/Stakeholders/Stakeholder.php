<?php
declare(strict_types=1);
namespace App\Blockchain\Consensus\Stakeholders;

use App\Blockchain\Interfaces\Consensus\SlashableInterface;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderId;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderName;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\SuspensionBlocks;

/**
 * Class Stakeholder
 *
 * Represents a stakeholder in the blockchain system.
 * A stakeholder has a unique identifier, a name, and a stake value.
 * This class provides methods to access and modify the stake, suspend the stakeholder,
 * and manage the suspension duration.
 *
 * @package App\Blockchain\Consensus\Stakeholders
 */
class Stakeholder implements SlashableInterface
{
   // private ?int $suspensionBlocks = null;
    private ?SuspensionBlocks $suspensionBlocks = null;

    public function __construct(
        private readonly StakeholderId   $id,
        private readonly StakeholderName $name,
        private StakeholderStake         $stake,
    ) { }

    public function getId(): StakeholderId
    {
        return $this->id;
    }

    public function getName(): StakeholderName
    {
        return $this->name;
    }

    public function getStake(): StakeholderStake
    {
        return $this->stake;
    }

    public function setStake(StakeholderStake $stake): void
    {
        $this->stake = $stake;
    }

    public function reduceStake(int $amount): void
    {
        $this->stake->reduce($amount);
    }

    public function suspend(SuspensionBlocks $blocks): void
    {
        $this->suspensionBlocks = $blocks;
    }

    public function unfreeze(): void
    {
        if ($this->suspensionBlocks !== null && $this->suspensionBlocks->value() > 0)
        {
            $this->suspensionBlocks->decrement();
            if ($this->suspensionBlocks->value() === 0)
            {
                $this->suspensionBlocks = null;
            }
        }
    }
}
