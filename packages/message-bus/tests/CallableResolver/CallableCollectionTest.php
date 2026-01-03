<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\CallableResolver;

class CallableCollectionTest extends TestCase
{
    /**
     * @var CallableResolver|MockObject
     */
    private $callableResolver;

    protected function setUp(): void
    {
        $this->callableResolver = $this->createMock(CallableResolver::class);
    }

    #[Test]
    public function itReturnsAnEmptyArrayIfNoCallablesAreDefined(): void
    {
        $collection = new CallableCollection([], $this->callableResolver);
        $this->assertSame([], $collection->filter('undefined_name'));
    }

    #[Test]
    public function itReturnsManyResolvedCallablesForAGivenName(): void
    {
        $message1Callable1 = function () {};
        $message1Callable2 = function () {};
        $collection = new CallableCollection(
            [
                'message1' => [
                    $message1Callable1,
                    $message1Callable2,
                ],
                'message2' => [
                    function () {},
                    function () {},
                ],
            ],
            $this->callableResolver
        );

        $matcher = $this->exactly(2);
        $this->callableResolver
            ->expects($matcher)
            ->method('resolve')
            ->willReturnCallback(function ($callable) use ($matcher, $message1Callable1, $message1Callable2) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($message1Callable1, $callable),
                    2 => $this->assertEquals($message1Callable2, $callable),
                    default => $this->fail('Unexpected number of invocations'),
                };

                return $callable;
            });

        $callables = $collection->filter('message1');

        $this->assertSame([$message1Callable1, $message1Callable2], $callables);
    }
}
