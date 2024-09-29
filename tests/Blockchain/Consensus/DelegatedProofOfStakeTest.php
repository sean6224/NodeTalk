<?php
declare(strict_types=1);
namespace App\Tests\Blockchain\Consensus;

use App\Blockchain\Consensus\DelegatedProofOfStake;
use App\Blockchain\Consensus\Stakeholders\Stakeholder;
use App\Blockchain\Exceptions\Consensus\ProofOfStakeException;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderId;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderName;
use App\Blockchain\ValueObject\Blockchain\Stakeholders\StakeholderStake;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

/**
 * @covers DelegatedProofOfStake
 */
class DelegatedProofOfStakeTest extends TestCase
{
    private DelegatedProofOfStake $dpos;

    /**
     * @before
     */
    protected function setUp(): void
    {
        $this->dpos = new DelegatedProofOfStake();
    }

    /**
     * Test adding a delegate to the DPoS system.
     *
     * @test
     * @covers DelegatedProofOfStake::voteForDelegate
     * @throws ProofOfStakeException
     */
    public function testVoteForDelegate(): void
    {
        $stakeholder = $this->createStakeholder('1', 'Alice', 100);

        $this->dpos->voteForDelegate($stakeholder);
        $delegates = $this->dpos->getDelegates();

        $this->assertCount(1, $delegates);
        $this->assertEquals('Alice', $delegates['1']->getName());
        $this->assertEquals(100, $delegates['1']->getStake());
    }

    /**
     * Test exceeding the maximum number of delegates.
     *
     * @test
     * @covers DelegatedProofOfStake::voteForDelegate
     * @covers ProofOfStakeException::MaxDelegatesExceeded
     */
    public function testVoteForDelegateThrowsExceptionWhenMaxDelegatesExceeded(): void
    {
        $this->expectException(ProofOfStakeException::class);
        $this->expectExceptionMessage('Maximum number of delegates reached.');

        for ($i = 1; $i <= 22; $i++) {
            $stakeholder = $this->createStakeholder((string)$i, 'Delegate ' . $i, 100);
            $this->dpos->voteForDelegate($stakeholder);
        }
    }

    /**
     * Test removing a delegate from the DPoS system by ID.
     *
     * @test
     * @covers DelegatedProofOfStake::removeDelegateById
     * @throws ProofOfStakeException
     */
    public function testRemoveDelegateById(): void
    {
        $stakeholder = $this->createStakeholder('1', 'bob', 150);

        $this->dpos->voteForDelegate($stakeholder);

        $this->assertCount(1, $this->dpos->getDelegates());

        $this->dpos->removeDelegateById('1');
        $this->assertCount(0, $this->dpos->getDelegates());
    }

    /**
     * Test selecting a delegate for block validation.
     *
     * @test
     * @covers DelegatedProofOfStake::selectDelegateForBlock
     * @throws ProofOfStakeException|RandomException
     */
    public function testSelectDelegateForBlock(): void
    {
        $stakeholder1 = $this->createStakeholder('1', 'Charlie', 100);
        $stakeholder2 = $this->createStakeholder('2', 'Diana', 300);

        $this->dpos->voteForDelegate($stakeholder1);
        $this->dpos->voteForDelegate($stakeholder2);

        $selectedDelegate = $this->dpos->selectDelegateForBlock();

        $this->assertInstanceOf(Stakeholder::class, $selectedDelegate);
        $this->assertTrue(
            in_array($selectedDelegate->getName(), ['Charlie', 'Diana'])
        );
    }

    /**
     * Test exception when selecting a delegate with no available delegates.
     *
     * @test
     * @covers DelegatedProofOfStake::selectDelegateForBlock
     * @covers ProofOfStakeException::NoDelegatesAvailable
     * @throws RandomException
     */
    public function testSelectDelegateThrowsExceptionWhenNoDelegates(): void
    {
        $this->expectException(ProofOfStakeException::class);
        $this->expectExceptionMessage('No delegates available.');

        $this->dpos->selectDelegateForBlock();
    }

    private function createStakeholder(string $id, string $name, int $stake): Stakeholder
    {
        return new Stakeholder(
            StakeholderId::fromString($id),
            StakeholderName::fromString($name),
            StakeholderStake::fromInt($stake)
        );
    }

}
