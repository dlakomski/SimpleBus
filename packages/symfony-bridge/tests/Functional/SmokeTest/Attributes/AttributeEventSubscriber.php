<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\EventListener;

#[EventListener]
final class AttributeEventSubscriber
{
    public function __invoke(AttrEvent $event): void
    {
        $event->setHandledBy($this);
    }
}
