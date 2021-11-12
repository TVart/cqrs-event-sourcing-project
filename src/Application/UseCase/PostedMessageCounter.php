<?php

namespace Kata\Application\UseCase;

use Kata\Domain\MessageEvent;

class PostedMessageCounter
{
    private $value = 0;

    public function handle(MessageEvent $event){
        if(is_a($event, MessagePosted::class)){
            $this->value++;
        }
        if(is_a($event, MessageDeleted::class)){
            $this->value--;
        }
    }

    public function getValue(){
        return $this->value;
    }
}