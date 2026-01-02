<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (MBConfig $config): void {
    $config->packageDirectories([__DIR__.'/packages']);
    $config->defaultBranch('main');
    $config->dataToAppend([
        ComposerJsonSection::REQUIRE_DEV => [
            'phpunit/phpunit' => '^11.5',
            'symplify/monorepo-builder' => '^12.4',
        ],
    ]);
};
