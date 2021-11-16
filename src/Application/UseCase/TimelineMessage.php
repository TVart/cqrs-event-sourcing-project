<?php

namespace Kata\Application\UseCase;

class TimelineMessage
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