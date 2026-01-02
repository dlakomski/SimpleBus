<?php

namespace SimpleBus\Asynchronous\Tests\Routing;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimpleBus\Asynchronous\Routing\EmptyRoutingKeyResolver;
use stdClass;

class EmptyRoutingKeyResolverTest extends TestCase
{
    #[Test]
    public function itReturnsAnEmptyRoutingKey(): void
    {
        $resolver = new EmptyRoutingKeyResolver();
        $this->assertSame('', $resolver->resolveRoutingKeyFor($this->messageDummy()));
    }

    private function messageDummy(): stdClass
    {
        return new stdClass();
    }
}
