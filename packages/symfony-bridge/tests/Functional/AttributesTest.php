<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrCommand;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrEvent;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributesTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        static::$class = null;
    }

    #[Test]
    public function itCanRegisterCommandHandlerUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $command = new AttrCommand();

        $commandBus = $container->get('command_bus');
        $this->assertInstanceOf(MessageBus::class, $commandBus);

        $commandBus->handle($command);

        $this->assertTrue($command->isHandled());
    }

    #[Test]
    public function itCanRegisterEventSubscriberUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $event = new AttrEvent();

        $eventBus = $container->get('event_bus');
        $this->assertInstanceOf(MessageBus::class, $eventBus);

        $eventBus->handle($event);

        $this->assertTrue($event->isHandledBy(
            SmokeTest\Attributes\AttributeEventSubscriber::class
        ));
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
