<?php

namespace Kata\Application\UseCase;

use Kata\Domain\MessageEvent;

class Timeline extends EventSubscriber
{
    public function __construct(string $eventType = MessagePosted::class)
    {
        parent::__construct($eventType);
    }

    /**
     * @var TimelineMessage[] $messages
     */
    private $messages=[];

    public function handle(MessageEvent $event): void
    {
        if(is_a($event, MessagePosted::class)){
            $this->messages[] = new TimelineMessage($event);
        }
        parent::handle($event);
    }

    /**
     * @return  TimelineMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

}