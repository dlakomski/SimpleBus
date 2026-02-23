<?php declare(strict_types=1);

namespace SimpleBus\SymfonyBridge\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class AsEventSubscriber
{
    public ?string $subscribesTo;
    public ?string $method;

    public function __construct(?string $subscribesTo = null, ?string $method = null)
    {
        $this->subscribesTo = $subscribesTo;
        $this->method = $method;
    }
}
