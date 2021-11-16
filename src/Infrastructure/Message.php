<?php

namespace Kata\Infrastructure;

use Kata\Application\UseCase\DecisionProjection;
use Kata\Application\UseCase\MessageDeleted;
use Kata\Application\UseCase\MessagePosted;
use Kata\Domain\EventPublisher;
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
     * @param EventPublisher $publisher
     * @param string $message
     */
    public function post(EventPublisher $publisher, string $message){
        $publisher->publish(new MessagePosted($message));
    }

    /**
     * @param EventPublisher $publisher
     */
    public function delete(EventPublisher $publisher){
        if($this->projection->isDeleted()) {
            return;
        }
        $this->publishAndApply($publisher, new MessageDeleted());
    }

    /**
     * @param EventPublisher $publisher
     * @param MessageDeleted $event
     */
    private function publishAndApply(EventPublisher $publisher, MessageDeleted $event){
        $publisher->publish($event);
        $this->projection->apply($event);
    }
}