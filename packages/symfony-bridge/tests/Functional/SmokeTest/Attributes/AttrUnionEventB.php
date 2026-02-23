<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

final class AttrUnionEventB
{
    /** @var class-string[] */
    private array $handled = [];

    public function isHandledBy(string $subscriber): bool
    {
        return in_array($subscriber, $this->handled, true);
    }

    public function setHandledBy(object $subscriber): void
    {
        $this->handled[] = get_class($subscriber);
    }
}
