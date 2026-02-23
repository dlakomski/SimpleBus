<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsAsynchronousEventSubscriber;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

#[AsAsynchronousEventSubscriber]
final class AttributeAsyncEventSubscriber
{
    public function __construct(private Spy $spy) {}

    public function __invoke(AttrEvent $event): void
    {
        $this->spy->handled[] = $event;
    }
}
