<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\CommandHandler;

#[CommandHandler]
final class AttributeUnionCommandHandler
{
    public function __invoke(AttrUnionCommandA|AttrUnionCommandB $command): void
    {
        $command->setHandled(true);
    }
}
