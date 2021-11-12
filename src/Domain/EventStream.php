<?php

namespace Kata\Domain;

interface EventStream
{
    public function add(MessageEvent $event): void;

    /**
     * @return MessageEvent[]
     */
    public function getEvents(): array;
}