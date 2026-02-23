<?php

namespace SimpleBus\SymfonyBridge\Tests\Functional\SmokeTest\Attributes;

final class AttrEvent
{
    /**
     * @var class-string[]
     */
    private array $handled = [];

    /**
     * @param class-string $subscriber
     */
    public function isHandledBy(string $subscriber): bool
    {
        return in_array($subscriber, $this->handled, true);
    }

    public function setHandledBy(object $subscriber): void
    {
        $this->handled[] = get_class($subscriber);
    }
}
