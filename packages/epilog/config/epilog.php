<?php

/*
 * Set specific configuration variables here
 */
return [
    'routes' => [
        'enabled' => true,
        'middleware' => config('laravolt.platform.middleware'),
        'prefix' => 'epilog',
    ],
    'view' => [
        'layout' => 'laravolt::layouts.app',
    ],
    'menu' => [
        'enabled' => true,
    ],
    'levels' => [
        'debug' => ['class' => 'grey'],
        'info' => ['class' => 'blue'],
        'notice' => ['class' => 'yellow'],
        'warning' => ['class' => 'orange'],
        'error' => ['class' => 'red'],
        'critical' => ['class' => 'black'],
        'alert' => ['class' => 'black'],
        'emergency' => ['class' => 'black'],
    ],
];
