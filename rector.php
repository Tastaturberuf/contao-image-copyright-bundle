<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/contao',
        __DIR__ . '/src',
    ])
    ->withFileExtensions(['php', 'html5'])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withPreparedSets(typeDeclarations: true)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
