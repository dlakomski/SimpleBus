<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\EventListener;

#[EventListener(method: 'on')]
final class AttributeUnionEventListenerOn
{
    public function on(AttrUnionEventC|AttrUnionEventD $event): void
    {
        $event->setHandledBy($this);
    }
}
