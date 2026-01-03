<?php

namespace SimpleBus\Message\Tests\Subscriber;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;
use stdClass;

class NotifiesMessageSubscribersMiddlewareTest extends TestCase
{
    #[Test]
    public function itNotifiesAllTheRelevantMessageSubscribers(): void
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2,
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $middleware = new NotifiesMessageSubscribersMiddleware($resolver);

        $next = new CallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame([$message], $next->receivedMessages());
        $this->assertSame([$message], $messageSubscriber1->receivedMessages());
        $this->assertSame([$message], $messageSubscriber2->receivedMessages());
    }

    #[Test]
    public function itLogsEveryCallToASubscriber(): void
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2,
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $logger = $this->createMock(LoggerInterface::class);
        $level = LogLevel::CRITICAL;

        $middleware = new NotifiesMessageSubscribersMiddleware($resolver, $logger, $level);

        $matcher = $this->exactly(4);
        $logger->expects($matcher)
            ->method('log')
            ->willReturnCallback(function ($actualLevel, $logMessage) use ($matcher, $level) {
                $this->assertEquals($level, $actualLevel);

                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals('Started notifying a subscriber', $logMessage),
                    2 => $this->assertEquals('Finished notifying a subscriber', $logMessage),
                    3 => $this->assertEquals('Started notifying a subscriber', $logMessage),
                    4 => $this->assertEquals('Finished notifying a subscriber', $logMessage),
                    default => $this->fail('Unexpected number of invocations'),
                };
            });

        $next = new CallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame([$message], $next->receivedMessages());
        $this->assertSame([$message], $messageSubscriber1->receivedMessages());
        $this->assertSame([$message], $messageSubscriber2->receivedMessages());
    }

    /**
     * @param CallableSpy[] $messageSubscribers
     *
     * @return MessageSubscribersResolver|MockObject
     */
    private function mockMessageSubscribersResolver(object $message, array $messageSubscribers)
    {
        $resolver = $this->createMock(MessageSubscribersResolver::class);

        $resolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->willReturn($messageSubscribers);

        return $resolver;
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
