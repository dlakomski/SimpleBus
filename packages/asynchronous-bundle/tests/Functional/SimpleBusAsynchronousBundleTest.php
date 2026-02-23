<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use SimpleBus\AsynchronousBundle\Tests\Functional\Attribute\AttrAsyncCommand;
use SimpleBus\AsynchronousBundle\Tests\Functional\Attribute\AttrEvent;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Serialization\Envelope\DefaultEnvelope;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SimpleBusAsynchronousBundleTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        static::$class = null;
    }

    #[Test]
    public function itNotifiesSynchronousEventSubscribersAndPublishesEvents(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $event = new DummyEvent();

        /** @var MessageBus $eventBus */
        $eventBus = $kernel->getContainer()->get('event_bus');
        $eventBus->handle($event);

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertSame([$event], $spy->handled);

        /** @var PublisherSpy $eventPublisher */
        $eventPublisher = $kernel->getContainer()->get('event_publisher_spy');
        $this->assertSame([$event], $eventPublisher->publishedMessages());
    }

    #[Test]
    public function itNotifiesAsynchronousEventSubscribersUsingAttributes(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $event = new AttrEvent();

        /** @var MessageBus $asynchronousEventBus */
        $asynchronousEventBus = $kernel->getContainer()->get('asynchronous_event_bus');
        $asynchronousEventBus->handle($event);

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertSame([$event], $spy->handled);

        /** @var PublisherSpy $eventPublisher */
        $eventPublisher = $kernel->getContainer()->get('event_publisher_spy');
        $this->assertSame([], $eventPublisher->publishedMessages());
    }

    #[Test]
    public function itHandlesAsynchronousCommandsUsingAttributes(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $command = new AttrAsyncCommand();

        /** @var MessageBus $asynchronousCommandBus */
        $asynchronousCommandBus = $kernel->getContainer()->get('asynchronous_command_bus');
        $asynchronousCommandBus->handle($command);

        /** @var PublisherSpy $commandPublisher */
        $commandPublisher = $kernel->getContainer()->get('command_publisher_spy');
        $this->assertSame([], $commandPublisher->publishedMessages());

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertSame([$command], $spy->handled);
    }

    #[Test]
    public function itNotifiesAsynchronousEventSubscribers(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $event = new DummyEvent();

        /** @var MessageBus $asynchronousEventBus */
        $asynchronousEventBus = $kernel->getContainer()->get('asynchronous_event_bus');
        $asynchronousEventBus->handle($event);

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertSame([$event], $spy->handled);

        /** @var PublisherSpy $eventPublisher */
        $eventPublisher = $kernel->getContainer()->get('event_publisher_spy');
        $this->assertSame([], $eventPublisher->publishedMessages());
    }

    #[Test]
    public function itOnlyPublishesUnhandledCommands(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $command = new DummyCommand();

        /** @var MessageBus $commandBus */
        $commandBus = $kernel->getContainer()->get('command_bus');
        $commandBus->handle($command);

        /** @var PublisherSpy $commandPublisher */
        $commandPublisher = $kernel->getContainer()->get('command_publisher_spy');
        $this->assertSame([$command], $commandPublisher->publishedMessages());
    }

    #[Test]
    public function itHandlesAsynchronousCommands(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $command = new DummyCommand();

        /** @var MessageBus $asynchronousCommandBus */
        $asynchronousCommandBus = $kernel->getContainer()->get('asynchronous_command_bus');
        $asynchronousCommandBus->handle($command);

        /** @var PublisherSpy $commandPublisher */
        $commandPublisher = $kernel->getContainer()->get('command_publisher_spy');
        $this->assertSame([], $commandPublisher->publishedMessages());

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertSame([$command], $spy->handled);
    }

    #[Test]
    public function itConsumesAsynchronousCommands(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $command = new DummyCommand();
        $envelope = DefaultEnvelope::forSerializedMessage(get_class($command), serialize($command));

        /** @var MessageConsumer $commandConsumer */
        $commandConsumer = $kernel->getContainer()->get('asynchronous_command_consumer');
        $commandConsumer->consume(serialize($envelope));

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertEquals([new DummyCommand()], $spy->handled);
    }

    #[Test]
    public function itConsumesAsynchronousEvents(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $event = new DummyEvent();
        $envelope = DefaultEnvelope::forSerializedMessage(get_class($event), serialize($event));

        /** @var MessageConsumer $commandConsumer */
        $commandConsumer = $kernel->getContainer()->get('asynchronous_event_consumer');
        $commandConsumer->consume(serialize($envelope));

        /** @var Spy $spy */
        $spy = $kernel->getContainer()->get('spy');
        $this->assertEquals([$event], $spy->handled);
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
