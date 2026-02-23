<?php

declare(strict_types=1);

namespace SimpleBus\SymfonyBridge\DependencyInjection;

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
        $resolvedMethod = $method ?? ($reflector instanceof ReflectionMethod ? $reflector->getName() : null);

        if (null === $messageType && $reflector instanceof ReflectionMethod) {
            $parameters = $reflector->getParameters();
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

        return [array_filter([
            $typeKey => $messageType,
            'method' => $resolvedMethod,
        ])];
    }
}
