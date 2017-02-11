<?php
/**
 *
 * Module.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 19:36
 */

use DoctrineCacheToolbar\Factory\Collector\CacheCollectorFactory;

return [
    'service_manager' => [
        'factories' => [
            'cache.toolbar' => CacheCollectorFactory::class
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'zend-developer-tools/toolbar/cache-data' => __DIR__.'/../view/zend-developer-tools/toolbar/cache-data.phtml',
        ]
    ],
    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'cache.toolbar' => 'cache.toolbar'
            ],
        ],
        'toolbar' => [
            'entries' => [
                'cache.toolbar' => 'zend-developer-tools/toolbar/cache-data'
            ],
        ],
    ],
];