<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $config): void {
    $config->packageDirectories([__DIR__.'/packages']);
    $config->defaultBranch('main');
    $config->dataToAppend([
        ComposerJsonSection::REQUIRE_DEV => [
            'phpunit/phpunit' => '^11.5.46',
            'symplify/monorepo-builder' => '^11.2',
        ],
    ]);

    $config->workers([
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
    ]);
};
