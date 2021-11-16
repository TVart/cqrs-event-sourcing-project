<?php

namespace Kata\Tests\Integration;

use Kata\Application\UseCase\EventsBus;
use Kata\Application\UseCase\EventSubscriber;
use Kata\Application\UseCase\MessageDeleted;
use Kata\Application\UseCase\MessagePosted;
use Kata\Application\UseCase\Timeline;
use Kata\Application\UseCase\TimelineMessage;
use Kata\Infrastructure\InMemory\InMemoryEventStream;
use Kata\Infrastructure\Message;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function givenApplicationWhenPostMessageThenShouldDisplayMessageInTimeline(){
        $eventBus = new EventsBus(new InMemoryEventStream());
        $timeline = new Timeline();
        $eventBus->subscribe($timeline);
        $inMemoryStream = new InMemoryEventStream();
        $message = new Message($inMemoryStream);
        $message->post($eventBus,"hello");
        $this->assertContainsOnlyInstancesOf(TimelineMessage::class, $timeline->getMessages());
        $this->assertEquals(new TimelineMessage("hello"), $timeline->getMessages()[0]);
    }

    /**
     * @test
     */
    public function givenEventBusWhenPublishEventsThenShouldStoreEvents()
    {
        $stream = new InMemoryEventStream();
        $eventsBus = new EventsBus($stream);
        $message = new MessagePosted('hello');
        $eventsBus->publish($message);
        $this->assertEquals(
            $message,
            $stream->getItemAtIndex(count($stream->getEvents())-1)
        );
        $this->assertCount(1, $stream->getEvents());
    }

    /**
     * @test
     */
    public function givenEventBusWhenPublishEventsThenCallEachHandlers(){
        $eventBus = new EventsBus(new InMemoryEventStream());
        $subscriber1 = new Timeline(MessagePosted::class);
        $subscriber2 = new Timeline(MessagePosted::class);
        $subscriber3 = new Timeline(MessageDeleted::class);
        $eventBus->subscribe($subscriber1);
        $eventBus->subscribe($subscriber2);
        $eventBus->subscribe($subscriber3);

        $eventBus->publish(new MessagePosted('hello'));

        $this->assertTrue($subscriber1->isCalled());
        $this->assertTrue($subscriber2->isCalled());
        $this->assertFalse($subscriber3->isCalled());
    }
}