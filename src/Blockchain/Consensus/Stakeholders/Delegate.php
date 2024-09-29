<?php
declare(strict_types=1);
namespace App\Blockchain\Consensus\Stakeholders;

use App\Blockchain\ValueObject\Blockchain\Stakeholders\ActiveStatus;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderId;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderName;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;

/**
 * Class Delegate
 *
 * Represents a delegate in the blockchain system, inheriting from Stakeholder.
 * A delegate has the same attributes as a stakeholder but also includes an
 * active status that determines if the delegate is currently eligible for block
 * validation.
 *
 * @package App\Blockchain\Consensus\Stakeholders
 */
class Delegate extends Stakeholder
{
    private ActiveStatus $isActive;

    public function __construct(
        private readonly StakeholderId   $id,
        private readonly StakeholderName $name,
        private readonly StakeholderStake $stake
    ) {
        parent::__construct(
            $this->id,
            $this->name,
            $this->stake
        );
        $this->isActive = ActiveStatus::fromBool(true);
    }

    public function activate(): void
    {
        $this->isActive = ActiveStatus::fromBool(true);
    }

    public function deactivate(): void
    {
        $this->isActive = ActiveStatus::fromBool(false);
    }

    public function isActive(): bool
    {
        return $this->isActive->value();
    }
}
