<?php

namespace Kata\Infrastructure;

use Kata\Application\UseCase\DecisionProjection;
use Kata\Application\UseCase\MessageDeleted;
use Kata\Application\UseCase\MessagePosted;
use Kata\Domain\EventStream;

class Message
{
    /**
     * @var DecisionProjection
     */
    private $projection;

    /**
     * @param EventStream $history
     */
    public function __construct(EventStream $history){
        $this->projection = new DecisionProjection($history);
    }


    /**
     * @param EventStream $history
     * @param string $message
     */
    public function post(EventStream $history, string $message){
        $history->add(new MessagePosted($message));
    }

    /**
     * @param EventStream $history
     */
    public function delete(EventStream $history){
        if($this->projection->isDeleted()) {
            return;
        }
        $this->publishAndApply($history, new MessageDeleted());
    }

    /**
     * @param EventStream $history
     * @param MessageDeleted $event
     */
    private function publishAndApply(EventStream $history, MessageDeleted $event){
        $history->add($event);
        $this->projection->apply($event);
    }
}