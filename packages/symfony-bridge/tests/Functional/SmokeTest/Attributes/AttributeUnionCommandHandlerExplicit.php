<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

use SimpleBus\SymfonyBridge\Attribute\CommandHandler;

#[CommandHandler(handles: AttrUnionCommandE::class)]
#[CommandHandler(handles: AttrUnionCommandF::class)]
final class AttributeUnionCommandHandlerExplicit
{
    public function __invoke(AttrUnionCommandE|AttrUnionCommandF $command): void
    {
        $command->setHandled(true);
    }
}
