<?php
declare(strict_types=1);
namespace App\Blockchain\Consensus\Stakeholders;

use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderId;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderName;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;

/**
 * Class Stakeholder
 *
 * Represents a stakeholder in the blockchain system.
 * A stakeholder has a unique identifier, a name, and a stake value.
 * This class provides methods to access and modify the stake.
 *
 * @package App\Blockchain\Consensus\Stakeholders
 */
class Stakeholder
{
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
}
