<?php
declare(strict_types=1);
namespace App\Blockchain\Audit;

use App\Blockchain\ValueObject\AuditType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use DateInterval;
use DateTime;
use DirectoryIterator;

/**
 * Class AuditLogger
 *
 * Provides a base class for logging audit events in the blockchain system.
 * Handles daily log file creation and automatic log file cleanup for files older than 7 days.
 *
 * @package App\Blockchain\Audit
 */
abstract class AuditLogger
{
    private AuditType $auditType;

    public function __construct(
        protected LoggerInterface $logger,
        private readonly string $logDir
    ) {
        $this->auditType = AuditType::fromClass($this);
        $this->logger = new MonologLogger('audit_logger');
        $this->logger->pushHandler($this->createDailyLogHandler());
        $this->deleteOldLogs();
    }

    protected function createDailyLogHandler(): StreamHandler
    {
        $filePath = $this->getLogFilePath();
        return new StreamHandler($filePath);
    }

    private function getLogFilePath(): string
    {
        return sprintf('%s/%s-%s.log', $this->logDir, $this->auditType, date('d.m.Y'));
    }

    private function deleteOldLogs(): void
    {
        $files = new DirectoryIterator($this->logDir);
        $now = new DateTime();
        $expirationPeriod = new DateInterval('P7D');

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'log') {
                $fileModificationTime = new DateTime();
                $fileModificationTime->setTimestamp($file->getMTime());

                if ($fileModificationTime < $now->sub($expirationPeriod)) {
                    unlink($file->getRealPath());
                }
            }
        }
    }

    protected function log(string $message, array $data): void
    {
        $this->logger->info($message, $data);
    }

    abstract public function logEvent(string $event, array $data): void;
}
