<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions;

use DateTimeImmutable;
use RuntimeException;
use Throwable;

/**
 * Class BaseException
 *
 * Base class for exceptions in the blockchain application.
 * Extends RuntimeException to add additional error information such as error code, context, and timestamp.
 */
class BaseException extends RuntimeException
{
    protected DateTimeImmutable $timestamp;

    public function __construct(
        string $message,
        protected string $errorCode = '',
        protected array $context = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->timestamp = new DateTimeImmutable();
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function logException(): void
    {
        $logMessage = sprintf(
            "Exception: %s\nCode: %s\nContext: %s\nTimestamp: %s",
            $this->getMessage(),
            $this->getErrorCode(),
            json_encode($this->getContext(), JSON_PRETTY_PRINT),
            $this->getTimestamp()->format('Y-m-d H:i:s')
        );

        error_log($logMessage);
    }

    public function formatError(): string
    {
        return sprintf(
            "Exception: %s\nCode: %s\nContext: %s\nTimestamp: %s",
            $this->getMessage(),
            $this->getErrorCode(),
            json_encode($this->getContext(), JSON_PRETTY_PRINT),
            $this->getTimestamp()->format('Y-m-d H:i:s')
        );
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
