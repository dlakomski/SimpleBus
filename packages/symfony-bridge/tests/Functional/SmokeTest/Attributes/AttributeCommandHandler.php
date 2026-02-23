<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AttributeCommandHandler
{
    public function __invoke(AttrCommand $command): void
    {
        $command->setHandled(true);
    }
}
