<?php

namespace Kata\Domain;

interface EventPublisher
{
    public function publish(MessageEvent $event);
}