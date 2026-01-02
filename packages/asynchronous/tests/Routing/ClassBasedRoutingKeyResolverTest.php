<?php

namespace SimpleBus\Asynchronous\Tests\Routing;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimpleBus\Asynchronous\Routing\ClassBasedRoutingKeyResolver;
use SimpleBus\Asynchronous\Tests\Routing\Fixtures\MessageDummy;

class ClassBasedRoutingKeyResolverTest extends TestCase
{
    #[Test]
    public function itReturnsTheFqcnWithDotsInsteadOfBackslashes(): void
    {
        $message = new MessageDummy();
        $resolver = new ClassBasedRoutingKeyResolver();

        $routingKey = $resolver->resolveRoutingKeyFor($message);

        $this->assertSame('SimpleBus.Asynchronous.Tests.Routing.Fixtures.MessageDummy', $routingKey);
    }
}
