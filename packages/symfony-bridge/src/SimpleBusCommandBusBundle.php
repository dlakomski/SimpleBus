<?php

namespace SimpleBus\SymfonyBridge;

use Reflector;
use SimpleBus\SymfonyBridge\Attribute\AsEventSubscriber;
use SimpleBus\SymfonyBridge\Attribute\AsMessageHandler;
use SimpleBus\SymfonyBridge\DependencyInjection\AttributeTagResolver;
use SimpleBus\SymfonyBridge\DependencyInjection\CommandBusExtension;
use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\AutoRegister;
use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\ConfigureMiddlewares;
use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\RegisterHandlers;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimpleBusCommandBusBundle extends Bundle
{
    private string $configurationAlias;

    public function __construct(string $alias = 'command_bus')
    {
        $this->configurationAlias = $alias;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(
            AsMessageHandler::class,
            static function (ChildDefinition $definition, AsMessageHandler $attribute, Reflector $reflector): void {
                foreach (AttributeTagResolver::resolveTags($reflector, $attribute->handles, $attribute->method, 'handles') as $tag) {
                    $definition->addTag('command_handler', $tag);
                }
            }
        );

        $container->registerAttributeForAutoconfiguration(
            AsEventSubscriber::class,
            static function (ChildDefinition $definition, AsEventSubscriber $attribute, Reflector $reflector): void {
                foreach (AttributeTagResolver::resolveTags($reflector, $attribute->subscribesTo, $attribute->method, 'subscribes_to') as $tag) {
                    $definition->addTag('event_subscriber', $tag);
                }
            }
        );

        $container->addCompilerPass(
            new AutoRegister('command_handler', 'handles'),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            10
        );

        $container->addCompilerPass(
            new ConfigureMiddlewares(
                'command_bus',
                'command_bus_middleware'
            )
        );

        $container->addCompilerPass(
            new RegisterHandlers(
                'simple_bus.command_bus.command_handler_map',
                'simple_bus.command_bus.command_handler_service_locator',
                'command_handler',
                'handles'
            )
        );
    }

    public function getContainerExtension(): CommandBusExtension
    {
        return new CommandBusExtension($this->configurationAlias);
    }
}
