<?php

namespace Kata\Application\UseCase;

use Kata\Domain\MessageEvent;

class MessagePosted implements MessageEvent
{
    /**
     * @var string
     */
    private $content;

    public function __construct(string $content){
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->content;
    }
}