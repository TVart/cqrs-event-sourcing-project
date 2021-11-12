<?php

namespace Kata\Tests;

use Kata\Application\UseCase\MessageDeleted;
use Kata\Application\UseCase\MessagePosted;
use Kata\Infrastructure\InMemory\InMemoryEventStream;
use Kata\Infrastructure\Message;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

class KataTest extends TestCase
{

    /**
     * @test
     */
    public function shouldRaiseItemAddedWhenAddItem()
    {
        /**
         * @var
         */
        $history = new InMemoryEventStream();
        $message = new Message($history);
        $message->post($history, 'Hello');
        $this->assertEquals(
            new MessagePosted('Hello'),
            $history->getItemAtIndex(count($history->getEvents())-1)
        );
        $this->assertCount(1, $history->getEvents());
    }

    /**
     * @test
     */
    public function givenMessageWhenDeleteMessageThenMessageShouldBeDeleted(){
        $history = new InMemoryEventStream();
        $history->add(new MessagePosted('Hello'));
        $message = new Message($history);
        $message->delete($history);
        $this->assertEquals(
            new MessageDeleted(),
            $history->getItemAtIndex(count($history->getEvents())-1)
        );
    }

    /**
     * @test
     */
    public function notRaiseMessageDeletedWhenDeleteDeletedMessage(){
        $history = new InMemoryEventStream();
        $history->add(new MessagePosted('Hello'));
        $history->add(new MessageDeleted());
        $message = new Message($history);
        $message->delete($history);
        $this->assertEquals(
            new MessageDeleted(),
            $history->getItemAtIndex(count($history->getEvents())-1)
        );
        $this->assertCount(2, $history->getEvents());
    }

    /**
     * @test
     */
    public function notRaiseMessageDeletedWhenTwiceDelete(){
        $history = new InMemoryEventStream();
        $history->add(new MessagePosted('Hello'));
        $history->add(new MessageDeleted());
        $message = new Message($history);
        $message->delete($history);
        $message->delete($history);
        $this->assertEquals(
            new MessageDeleted(),
            $history->getItemAtIndex(count($history->getEvents())-1)
        );
        $this->assertCount(2, $history->getEvents());
    }
}