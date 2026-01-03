<?php

namespace SimpleBus\Message\Tests\Name;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage;

class ClassBasedNameResolverTest extends TestCase
{
    #[Test]
    public function itReturnsTheFullClassNameAsTheUniqueNameOfAMessage(): void
    {
        $resolver = new ClassBasedNameResolver();
        $message = new DummyMessage();
        $this->assertSame(
            DummyMessage::class,
            $resolver->resolve($message)
        );
    }
}
