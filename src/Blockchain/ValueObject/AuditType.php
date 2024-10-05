<?php
declare(strict_types=1);
namespace App\Blockchain\ValueObject;

use ReflectionClass;

final class AuditType
{
    protected function __construct(
        protected string $value
    ) {
    }

    public static function fromClass(object $auditInstance): self
    {
        return new self((new ReflectionClass($auditInstance))->getShortName());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
