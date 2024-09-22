<?php
declare(strict_types=1);
namespace App\Shared\Bus\Command;

/**
 * Class CommandResult
 *
 * Represents the result of executing a command.
 *
 * @template T
 *
 * @property ?array $result Contains the data returned after executing the command.
 * @property bool $successful Indicates whether the command execution was successful.
 */
final readonly class CommandResult
{
    public function __construct(
        private ?array $result,
        private bool $successful,
    ) {
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getMessage(): mixed
    {
        return $this->result['message'] ?? null;
    }
}
