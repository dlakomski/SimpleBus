<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsyncEventListener;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

#[AsyncEventListener]
final class AttributeAsyncEventSubscriber
{
    public function __construct(private Spy $spy) {}

    public function __invoke(AttrEvent $event): void
    {
        $this->spy->handled[] = $event;
    }
}
