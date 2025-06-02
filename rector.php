<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/contao',
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withPreparedSets(
        true,
        true,
        true,
        true,
        true,
        false,
        true,
        true,
        true,
        true,
        true,
        true,
        true,
        true,
        true
    )
    ->withSkip([
        DeclareStrictTypesRector::class => [
            __DIR__ . '/contao/templates/*.html5'
        ],
        FlipTypeControlToUseExclusiveTypeRector::class,
        RemoveUnusedPrivateMethodParameterRector::class => [
            __DIR__ . '/src/DataContainer/FilesDataContainer.php',
        ]
    ]);
