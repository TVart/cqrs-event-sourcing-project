<?php

namespace Kata\Application\UseCase;

use Kata\Domain\EventStream;
use Kata\Domain\MessageEvent;

class DecisionProjection
{
    private $isDeleted = false;

    public function __construct(EventStream $history)
    {
        foreach ($history->getEvents() as $event){
            if(is_a($event, MessageDeleted::class)){
                $this->apply($event);
            }
        }
    }

    public function isDeleted(): bool{
        return $this->isDeleted;
    }

    public function apply(MessageEvent $event){
        if(is_a($event, MessageDeleted::class)){
            $this->isDeleted = true;
        }
    }
}