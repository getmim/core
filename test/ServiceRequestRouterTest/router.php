<?php

return (object)[
    // CLI
    
    'cli-module' => (object)[
        'cliModuleIndex' => (object)[
            'name' => 'cliModuleIndex',
            'path' => (object)[
                'value' => 'module',
                'params' => (object)[],
                '_type' => 'text'
            ],
            'handler' => 'Module::index',
            '_handler' => (object)[
                'class' => 'ModuleController',
                'action' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'ModuleController', 'action'=>'indexAction', 'solved'=>false]
            ]
        ],
        'cliModuleInfo' => (object)[
            'name' => 'cliModuleInfo',
            'path' => (object)[
                'value' => 'module :name',
                'params' => (object)[
                    'name' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^module (?<name>[a-zA-Z0-9-_]+)$!'
            ],
            'handler' => 'Module::info',
            '_handler' => (object)[
                'class' => 'ModuleController',
                'action' => 'infoAction'
            ],
            '_handlers' => [
                (object)['class'=>'ModuleController', 'action'=>'infoAction', 'solved'=>false]
            ]
        ]
    ],
    
    'cli-helper' => (object)[
        'cliInfo' => (object)[
            'name' => 'cliInfo',
            'path' => (object)[
                'value' => ':action',
                'params' => (object)[
                    'action' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^(?<action>[a-zA-Z0-9-_]+)$!'
            ],
            'handler' => 'CLI::info',
            '_handler' => (object)[
                'class' => 'CLIController',
                'action' => 'infoAction'
            ],
            '_handlers' => [
                (object)['class'=>'CLIController', 'action'=>'infoAction', 'solved'=>false]
            ]
        ],
        'cliInfoSingle' => (object)[
            'name' => 'cliInfoSingle',
            'path' => (object)[
                'value' => ':action :info',
                'params' => (object)[
                    'action' => 'slug',
                    'info' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^(?<action>[a-zA-Z0-9-_]+) (?<info>[a-zA-Z0-9-_]+)$!'
            ],
            'handler' => 'CLI::single',
            '_handler' => (object)[
                'class' => 'CLIController',
                'action' => 'singleAction'
            ],
            '_handlers' => [
                (object)['class'=>'CLIController', 'action'=>'singleAction', 'solved'=>false]
            ]
        ]
    ],
    
    // HTTP
    
    'site' => (object)[
        'siteHome' => (object)[
            'name' => 'siteHome',
            'path' => (object)[
                'value' => '/',
                'params' => (object)[],
                '_type' => 'text'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'Site::index',
            '_handler' => (object)[
                'class' => 'SiteController',
                'method' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'SiteController', 'method'=>'indexAction', 'solved'=>false]
            ]
        ],
        'siteHomeCreate' => (object)[
            'name' => 'siteHomeCreate',
            'path' => (object)[
                'value' => '/',
                'params' => (object)[],
                '_type' => 'text'
            ],
            'method' => 'POST',
            '_method' => ['POST'],
            'handler' => 'Site::create',
            '_handler' => (object)[
                'class' => 'SiteController',
                'method' => 'createAction'
            ],
            '_handlers' => [
                (object)['class'=>'SiteController', 'method'=>'createAction', 'solved'=>false]
            ]
        ],
        'sitePageSingle' => (object)[
            'name' => 'sitePageSingle',
            'path' => (object)[
                'value' => '/page/:slug',
                'params' => (object)[
                    'slug' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^\/page\/(?<slug>[a-zA-Z0-9-_]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'Page::single',
            '_handler' => (object)[
                'class' => 'PageController',
                'method' => 'singleAction'
            ],
            '_handlers' => [
                (object)['class'=>'PageController', 'method'=>'singleAction', 'solved'=>false]
            ]
        ]
    ],
    
    'admin-module' => (object)[
        'adminModuleIndex' => (object)[
            'name' => 'adminModuleIndex',
            'path' => (object)[
                'value' => '/admin/:module',
                'params' => (object)[
                    'module' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^\/admin\/(?<module>[a-zA-Z0-9-_]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'AdminModule::index',
            '_handler' => (object)[
                'class' => 'AdminModuleController',
                'method' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'AdminModuleController', 'method'=>'indexAction', 'solved'=>false]
            ]
        ],
        'adminModuleIndexInfo' => (object)[
            'name' => 'adminModuleIndexInfo',
            'path' => (object)[
                'value' => '/admin/:module/:info',
                'params' => (object)[
                    'module' => 'slug',
                    'info' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^\/admin\/(?<module>[a-zA-Z0-9-_]+)\/(?<info>[a-zA-Z0-9-_]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'AdminModule::info',
            '_handler' => (object)[
                'class' => 'AdminModuleController',
                'method' => 'infoAction'
            ],
            '_handlers' => [
                (object)['class'=>'AdminModuleController', 'method'=>'infoAction', 'solved'=>false]
            ]
        ]
    ],

    'user-web' => (object)[
        'userWebHome' => (object)[
            'name' => 'userWebHome',
            'path' => (object)[
                'value' => '/',
                'params' => (object)[],
                '_type' => 'text'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'UserWeb::index',
            '_handler' => (object)[
                'class' => 'UserWebController',
                'action' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'UserWebController', 'action' => 'indexAction', 'solved'=>false]
            ]
        ],
        'userWebPostSingle' => (object)[
            'name' => 'userWebPostSingle',
            'path' => (object)[
                'value' => '/post/:slug',
                'params' => (object)[
                    'slug' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^\/post\/(?<slug>[a-zA-Z0-9-_]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'UserWebPost::single',
            '_handler' => (object)[
                'class' => 'UserWebPostController',
                'action' => 'singleAction'
            ],
            '_handlers' => [
                (object)['class'=>'UserWebPostController', 'action' => 'singleAction', 'solved'=>false]
            ]
        ]
    ],

    'api-version' => (object)[
        'apiVersionHome' => (object)[
            'name' => 'apiVersionHome',
            'path' => (object)[
                'value' => '/:version',
                'params' => (object)[
                    'version' => 'number'
                ],
                '_type' => 'regex',
                '_value' => '!^\/(?<version>[0-9]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'ApiVersion::index',
            '_handler' => (object)[
                'class' => 'ApiVersionController',
                'method' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'ApiVersionController', 'method'=>'indexAction', 'solved'=>false]
            ]
        ],
        'apiVersionInfoIndex' => (object)[
            'name' => 'apiVersionInfoIndex',
            'path' => (object)[
                'value' => '/:version/info',
                'params' => (object)[
                    'version' => 'number'
                ],
                '_type' => 'regex',
                '_value' => '!^\/(?<version>[0-9]+)\/info$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'ApiVersion::index',
            '_handler' => (object)[
                'class' => 'ApiVersionController',
                'method' => 'indexAction'
            ],
            '_handlers' => [
                (object)['class'=>'ApiVersionController', 'method'=>'indexAction', 'solved'=>false]
            ]
        ],
        'apiVersionInfoSingle' => (object)[
            'name' => 'apiVersionInfoSingle',
            'path' => (object)[
                'value' => '/:version/info/:info',
                'params' => (object)[
                    'version' => 'number',
                    'info' => 'slug'
                ],
                '_type' => 'regex',
                '_value' => '!^\/(?<version>[0-9]+)\/info\/(?<info>[a-zA-Z0-9-_]+)$!'
            ],
            'method' => 'GET',
            '_method' => ['GET'],
            'handler' => 'ApiVersion::info',
            '_handler' => (object)[
                'class' => 'ApiVersionController',
                'method' => 'infoAction'
            ],
            '_handlers' => [
                (object)['class'=>'ApiVersionController', 'method'=>'infoAction', 'solved'=>false]
            ]
        ]
    ]
];