<?php

namespace Kata\Tests\Unit;

use Kata\Application\UseCase\EventsBus;
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
        $stream = new InMemoryEventStream();
        $eventsBus = new EventsBus($stream);
        $message = new Message($stream);
        $message->post($eventsBus, 'Hello');
        $this->assertEquals(
            new MessagePosted('Hello'),
            $stream->getItemAtIndex(count($stream->getEvents())-1)
        );
        $this->assertCount(1, $stream->getEvents());
    }

    /**
     * @test
     */
    public function givenMessageWhenDeleteMessageThenMessageShouldBeDeleted(){
        $stream = new InMemoryEventStream();
        $eventsBus = new EventsBus($stream);
        $stream->add(new MessagePosted('Hello'));
        $message = new Message($stream);
        $message->delete($eventsBus);
        $this->assertEquals(
            new MessageDeleted(),
            $stream->getItemAtIndex(count($stream->getEvents())-1)
        );
    }

    /**
     * @test
     */
    public function notRaiseMessageDeletedWhenDeleteDeletedMessage(){
        $stream = new InMemoryEventStream();
        $eventsBus = new EventsBus($stream);
        $stream->add(new MessagePosted('Hello'));
        $stream->add(new MessageDeleted());
        $message = new Message($stream);
        $message->delete($eventsBus);
        $this->assertEquals(
            new MessageDeleted(),
            $stream->getItemAtIndex(count($stream->getEvents())-1)
        );
        $this->assertCount(2, $stream->getEvents());
    }

    /**
     * @test
     */
    public function notRaiseMessageDeletedWhenTwiceDelete(){
        $stream = new InMemoryEventStream();
        $eventsBus = new EventsBus($stream);
        $stream->add(new MessagePosted('Hello'));
        $stream->add(new MessageDeleted());
        $message = new Message($stream);
        $message->delete($eventsBus);
        $message->delete($eventsBus);
        $this->assertEquals(
            new MessageDeleted(),
            $stream->getItemAtIndex(count($stream->getEvents())-1)
        );
        $this->assertCount(2, $stream->getEvents());
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