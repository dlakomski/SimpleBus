<?php

declare(strict_types=1);

namespace SimpleBus\SymfonyBridge\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class CommandHandler
{
    public ?string $handles;
    public ?string $method;

    public function __construct(?string $handles = null, ?string $method = null)
    {
        $this->handles = $handles;
        $this->method = $method;
    }
}
