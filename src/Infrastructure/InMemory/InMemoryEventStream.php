<?php

namespace Kata\Infrastructure\InMemory;

use Kata\Domain\EventStream;
use Kata\Domain\MessageEvent;

class InMemoryEventStream implements EventStream
{

    /**
     * @var MessageEvent[] $history
     */
    private $history = [];

    public function add(MessageEvent $event): void
    {
        $this->history[] = $event;
    }

    /**
     * @return MessageEvent[]
     */
    public function getEvents(): array
    {
        return $this->history;
    }

    /**
     * @param int $index
     * @return MessageEvent|null
     */
    public function getItemAtIndex(int $index){
        return isset($this->history[$index]) ? $this->history[$index] :  null;
    }
}