<?php

namespace Kata\Tests\Unit;

use Kata\Application\UseCase\MessageDeleted;
use Kata\Application\UseCase\MessagePosted;
use Kata\Application\UseCase\PostedMessageCounter;
use Kata\Application\UseCase\Timeline;
use Kata\Application\UseCase\TimelineMessage;
use Kata\Infrastructure\InMemory\InMemoryEventStream;
use Kata\Infrastructure\Message;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class MessageTest extends TestCase
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

    /**
     * @test
     */
    public function givenMessageWhenMessagePosterThenCounterShouldBeIncremented()
    {
        $counter = new PostedMessageCounter();
        $counter->handle(new MessagePosted('hello'));
        $this->assertEquals(1, $counter->getValue());
    }

    /**
     * @test
     */
    public function givenMessageWhenMessageDeletedThenCounterShouldBeDecremented()
    {
        $counter = new PostedMessageCounter();
        $counter->handle(new MessagePosted('hello'));
        $counter->handle(new MessagePosted('hello'));
        $counter->handle(new MessageDeleted());
        $this->assertEquals(1, $counter->getValue());
    }

    /**
     * @test
     */
    public function givenMessageWhenMessagePostedThenTimelineShouldDisplayMessage()
    {
        $timeline = new Timeline();
        $postedMessage = new MessagePosted("hello");
        $timeline->handle($postedMessage);
        $this->assertContainsOnlyInstancesOf(TimelineMessage::class, $timeline->getMessages());
        $this->assertEquals(new TimelineMessage($postedMessage), $timeline->getMessages()[0]);
    }
}