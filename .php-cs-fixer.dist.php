<?php

declare(strict_types=1);

use Palmtree\PhpCsFixerConfig\Config;

$config = new Config();

$config
    ->getFinder()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/examples')
    ->in(__DIR__ . '/tests')
    ->append([__FILE__])
;

return $config;
