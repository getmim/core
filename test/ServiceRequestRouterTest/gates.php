<?php

return [
    /* CLI */
    
    // cli-module
    (object)[
        'name' => 'cli-module',
        'host' => (object)[
            'value' => 'CLI',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => 'module',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ],
    
    // cli-helper
    (object)[
        'name' => 'cli-helper',
        'host' => (object)[
            'value' => 'CLI',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => ':action',
            'params' => (object)[
                'action' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^(?<action>[a-zA-Z0-9-_]+)(( .*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ],
    
    /* HTTP */
    
    // api-version
    (object)[
        'name' => 'api-version',
        'host' => (object)[
            'value' => 'api.core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/:version',
            'params' => (object)[
                'version' => 'number'
            ],
            '_type' => 'regex',
            '_value' => '!^\/(?<version>[0-9]+)((\/.*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ],
    
    // user-web
    (object)[
        'name' => 'user-web',
        'host' => (object)[
            'value' => ':user.core.mim',
            'params' => (object)[
                'user' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^(?<user>[a-zA-Z0-9-_]+)\.core\.mim$!'
        ],
        'path' => (object)[
            'value' => '/',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ],
    
    // admin-module
    (object)[
        'name' => 'admin-module',
        'host' => (object)[
            'value' => 'core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/admin/:module',
            'params' => (object)[
                'module' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^\/admin\/(?<module>[a-zA-Z0-9-_]+)((\/.*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ],
    
    // site
    (object)[
        'name' => 'site',
        'host' => (object)[
            'value' => 'core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                'name' => '404',
                '_handlers' => []
            ],
            '500' => (object)[
                'name' => '500',
                '_handlers' => []
            ]
        ]
    ]
];