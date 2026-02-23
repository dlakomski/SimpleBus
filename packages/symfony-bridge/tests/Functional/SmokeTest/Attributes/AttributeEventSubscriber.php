<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\AsEventSubscriber;

#[AsEventSubscriber]
final class AttributeEventSubscriber
{
    public function __invoke(AttrEvent $event): void
    {
        $event->setHandledBy($this);
    }
}
