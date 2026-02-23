<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\CommandHandler;

#[CommandHandler(method: 'process')]
final class AttributeUnionCommandHandlerProcess
{
    public function process(AttrUnionCommandC|AttrUnionCommandD $command): void
    {
        $command->setHandled(true);
    }
}
