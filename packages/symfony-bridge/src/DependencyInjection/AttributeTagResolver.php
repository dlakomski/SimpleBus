<?php

declare(strict_types=1);

namespace SimpleBus\SymfonyBridge\DependencyInjection;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use Reflector;

final class AttributeTagResolver
{
    /**
     * @return list<array<string, string>>
     */
    public static function resolveTags(
        Reflector $reflector,
        ?string $messageType,
        ?string $method,
        string $typeKey,
    ): array {
        $resolvedMethod = $method
            ?? ($reflector instanceof ReflectionMethod ? $reflector->getName() : null)
            ?? ($reflector instanceof ReflectionClass && $reflector->hasMethod('handle') && !$reflector->hasMethod('__invoke') ? 'handle' : null);

        if (null === $messageType) {
            $reflectionMethod = match (true) {
                $reflector instanceof ReflectionMethod => $reflector,
                $reflector instanceof ReflectionClass && null !== $method => $reflector->getMethod($method),
                $reflector instanceof ReflectionClass && $reflector->hasMethod('__invoke') => $reflector->getMethod('__invoke'),
                $reflector instanceof ReflectionClass && $reflector->hasMethod('handle') => $reflector->getMethod('handle'),
                default => null,
            };

            if (null !== $reflectionMethod) {
                $parameters = $reflectionMethod->getParameters();
                if (1 === count($parameters) && !$parameters[0]->isOptional()) {
                    $type = $parameters[0]->getType();

                    $types = match (true) {
                        $type instanceof ReflectionNamedType => [$type],
                        $type instanceof ReflectionUnionType => $type->getTypes(),
                        default => [],
                    };

                    $tags = [];
                    foreach ($types as $namedType) {
                        if (!$namedType instanceof ReflectionNamedType || $namedType->isBuiltin()) {
                            continue;
                        }
                        $tags[] = array_filter([
                            $typeKey => $namedType->getName(),
                            'method' => $resolvedMethod,
                        ]);
                    }

                    return $tags;
                }
            }
        }

        return [array_filter([
            $typeKey => $messageType,
            'method' => $resolvedMethod,
        ])];
    }
}
