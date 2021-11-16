<?php

namespace Kata\Application\UseCase;

use Kata\Domain\EventPublisher;
use Kata\Domain\EventStream;
use Kata\Domain\MessageEvent;
use MongoDB\Driver\Monitoring\Subscriber;

class EventsBus implements EventPublisher
{
    /**
     * @var EventStream $stream
     */
    private $stream;

    /**
     * @var EventSubscriber[]
     */
    private $subscribers=[];

    public function __construct(EventStream $stream)
    {
        $this->stream = $stream;
    }

    public function publish(MessageEvent $event){
        $this->stream->add($event);
        foreach ($this->subscribers as $subscriber){
            if(is_a($event, $subscriber->getEventType())){
                $subscriber->handle($event);
            }
        }
    }

    public function subscribe(EventSubscriber $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }
}