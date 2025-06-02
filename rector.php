<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/contao',
        __DIR__ . '/src',
    ])
    ->withFileExtensions(['php', 'html5'])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withPreparedSets(
        true,
        true,
        true,
        typeDeclarations: true
    )
    ->withSkip([
        FlipTypeControlToUseExclusiveTypeRector::class,
        RemoveUnusedPrivateMethodParameterRector::class => [
            __DIR__ . '/src/DataContainer/FilesDataContainer.php',
        ]
    ]);
