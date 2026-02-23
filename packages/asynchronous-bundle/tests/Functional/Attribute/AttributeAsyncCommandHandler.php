<?php

namespace SimpleBus\AsynchronousBundle\Tests\Functional\Attribute;

use SimpleBus\AsynchronousBundle\Attribute\AsAsynchronousMessageHandler;
use SimpleBus\AsynchronousBundle\Tests\Functional\Spy;

final class AttributeAsyncCommandHandler
{
    public function __construct(private Spy $spy) {}

    #[AsAsynchronousMessageHandler]

    public function handle(AttrAsyncCommand $command): void
    {
        $this->spy->handled[] = $command;
    }
}
