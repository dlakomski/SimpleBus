<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsyncCommandHandler;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

#[AsyncCommandHandler(handles: AttrAsyncCommandE::class, method: 'handle')]
final class AttributeUnionAsyncCommandHandlerExplicit
{
    public function __construct(private Spy $spy) {}

    public function handle(AttrAsyncCommandE|AttrAsyncCommandF $command): void
    {
        $this->spy->handled[] = $command;
    }
}
