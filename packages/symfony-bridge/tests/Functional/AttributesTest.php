<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrCommand;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrEvent;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandA;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandB;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandC;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandD;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandE;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionCommandF;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventA;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventB;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventC;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventD;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventG;
use SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes\AttrUnionEventH;
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

    #[Test]
    public function itCanRegisterUnionCommandHandlersUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $a = new AttrUnionCommandA();
        $b = new AttrUnionCommandB();

        /** @var MessageBus $commandBus */
        $commandBus = $container->get('command_bus');
        $commandBus->handle($a);
        $commandBus->handle($b);

        $this->assertTrue($a->isHandled());
        $this->assertTrue($b->isHandled());
    }

    #[Test]
    public function itCanRegisterUnionCommandHandlersWithMethodUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $c = new AttrUnionCommandC();
        $d = new AttrUnionCommandD();

        /** @var MessageBus $commandBus */
        $commandBus = $container->get('command_bus');
        $commandBus->handle($c);
        $commandBus->handle($d);

        $this->assertTrue($c->isHandled());
        $this->assertTrue($d->isHandled());
    }

    #[Test]
    public function itHonorsExplicitHandlesOnUnionCommandHandler(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $e = new AttrUnionCommandE();
        $f = new AttrUnionCommandF();

        /** @var MessageBus $commandBus */
        $commandBus = $container->get('command_bus');
        $commandBus->handle($e);
        $commandBus->handle($f);

        $this->assertTrue($e->isHandled());
        $this->assertTrue($f->isHandled());
    }

    #[Test]
    public function itCanRegisterUnionEventListenersUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $a = new AttrUnionEventA();
        $b = new AttrUnionEventB();

        /** @var MessageBus $eventBus */
        $eventBus = $container->get('event_bus');
        $eventBus->handle($a);
        $eventBus->handle($b);

        $this->assertTrue($a->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListener::class));
        $this->assertTrue($b->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListener::class));
    }

    #[Test]
    public function itCanRegisterUnionEventListenersWithMethodUsingAttribute(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $c = new AttrUnionEventC();
        $d = new AttrUnionEventD();

        /** @var MessageBus $eventBus */
        $eventBus = $container->get('event_bus');
        $eventBus->handle($c);
        $eventBus->handle($d);

        $this->assertTrue($c->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListenerOn::class));
        $this->assertTrue($d->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListenerOn::class));
    }

    #[Test]
    public function itHonorsExplicitSubscribesToOnUnionEventListener(): void
    {
        self::bootKernel(['environment' => 'config_attributes']);
        $container = self::getContainer();

        $g = new AttrUnionEventG();
        $h = new AttrUnionEventH();

        /** @var MessageBus $eventBus */
        $eventBus = $container->get('event_bus');
        $eventBus->handle($g);
        $eventBus->handle($h);

        $this->assertTrue($g->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListenerExplicit::class));
        $this->assertFalse($h->isHandledBy(SmokeTest\Attributes\AttributeUnionEventListenerExplicit::class), 'Event not explicitly subscribed should not be handled');
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
