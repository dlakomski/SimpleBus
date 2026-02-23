<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsyncCommandHandler;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

#[AsyncCommandHandler(method: 'process')]
final class AttributeUnionAsyncCommandHandlerProcess
{
    public function __construct(private Spy $spy) {}

    public function process(AttrAsyncCommandC|AttrAsyncCommandD $command): void
    {
        $this->spy->handled[] = $command;
    }
}
