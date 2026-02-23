<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsyncCommandHandler;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

#[AsyncCommandHandler(method: 'handle')]
final class AttributeUnionAsyncCommandHandler
{
    public function __construct(private Spy $spy) {}

    public function handle(AttrAsyncCommandA|AttrAsyncCommandB $command): void
    {
        $this->spy->handled[] = $command;
    }
}
