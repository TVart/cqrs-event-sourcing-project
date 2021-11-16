<?php

namespace Kata\Application\UseCase;

use Kata\Domain\MessageEvent;

class EventSubscriber
{

    private $isCalled = false;
    private $eventType;

    public function __construct(string $eventType)
    {
        $this->eventType = $eventType;
    }

    public function handle(): void
    {
        $this->isCalled = true;
    }

    /**
     * @return bool
     */
    public function isCalled(): bool
    {
        return $this->isCalled;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }
}