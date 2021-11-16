<?php

namespace Kata\Application\UseCase;

use Kata\Domain\MessageEvent;

class Timeline
{
    /**
     * @var TimelineMessage[] $messages
     */
    private $messages;

    public function handle(MessageEvent $event){
        if(is_a($event, MessagePosted::class)){
            $this->messages[] = new TimelineMessage($event);
        }
    }

    /**
     * @return  TimelineMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

}