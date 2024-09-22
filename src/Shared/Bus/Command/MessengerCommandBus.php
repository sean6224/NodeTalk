<?php
declare(strict_types=1);
namespace App\Shared\Bus\Command;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * The MessengerCommandBus class implements the MessageBusInterface,
 * allowing for dispatching and handling messages in the system.
 *
 * @template T
 */
readonly class MessengerCommandBus implements MessageBusInterface
{
    public function __construct(
        private MessageBusInterface $innerBus
    ) {
    }

    public function dispatch(object $message, array $stamps = []): Envelope
    {
        return $this->innerBus->dispatch($message, $stamps);
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(object $message, bool $includeMessage = false, array $stamps = []): CommandResult
    {
        $envelope = $this->dispatch($message, $stamps);
        $handledStamp = $envelope->last(HandledStamp::class);

        if ($handledStamp !== null) {
            if ($includeMessage) {
                return new CommandResult(['message' => $envelope->getMessage()], true);
            }

            return new CommandResult(null, true);
        }

        return new CommandResult(null, false);
    }
}
