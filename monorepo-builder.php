<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $config): void {
    $config->packageDirectories([__DIR__.'/packages']);
    $config->defaultBranch('main');
    $config->dataToAppend([
        ComposerJsonSection::REQUIRE_DEV => [
            'phpunit/phpunit' => '^11.5.46',
            'symplify/monorepo-builder' => '^11.2',
        ],
    ]);
};
