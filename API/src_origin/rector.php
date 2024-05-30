<?php
 
declare(strict_types=1);
 
use Rector\Config\RectorConfig;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
 
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);
 
    $rectorConfig->rule(RedirectRouteToToRouteHelperRector::class);
};
