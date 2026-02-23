<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\EventListener;

#[EventListener]
final class AttributeUnionEventListener
{
    public function __invoke(AttrUnionEventA|AttrUnionEventB $event): void
    {
        $event->setHandledBy($this);
    }
}
