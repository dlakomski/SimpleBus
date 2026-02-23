<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\EventListener;

#[EventListener(subscribesTo: AttrUnionEventG::class)]
final class AttributeUnionEventListenerExplicit
{
    public function __invoke(AttrUnionEventG|AttrUnionEventH $event): void
    {
        $event->setHandledBy($this);
    }
}
