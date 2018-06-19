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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // cli
    (object)[
        'name' => 'cli',
        'host' => (object)[
            'value' => 'CLI',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    /* HTTP */
    
    // api-version-media-binary
    (object)[
        'name' => 'api-version-media-binary',
        'host' => (object)[
            'value' => 'api.core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/media/:version/binary',
            'params' => (object)[
                'version' => 'number'
            ],
            '_type' => 'regex',
            '_value' => '!^\/media\/(?<version>[0-9]+)\/binary((\/.*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // api
    (object)[
        'name' => 'api',
        'host' => (object)[
            'value' => 'api.core.mim',
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // user-web-admin-module
    (object)[
        'name' => 'user-web-admin-module',
        'host' => (object)[
            'value' => ':user.core.mim',
            'params' => (object)[
                'user' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^(?<user>[a-zA-Z0-9-_]+)\.core\.mim$!'
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // user-web-admin
    (object)[
        'name' => 'user-web-admin',
        'host' => (object)[
            'value' => ':user.core.mim',
            'params' => (object)[
                'user' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^(?<user>[a-zA-Z0-9-_]+)\.core\.mim$!'
        ],
        'path' => (object)[
            'value' => '/admin',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // user-web-module
    (object)[
        'name' => 'user-web-module',
        'host' => (object)[
            'value' => ':user.core.mim',
            'params' => (object)[
                'user' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^(?<user>[a-zA-Z0-9-_]+)\.core\.mim$!'
        ],
        'path' => (object)[
            'value' => '/:module',
            'params' => (object)[
                'module' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^\/(?<module>[a-zA-Z0-9-_]+)((\/.*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // admin
    (object)[
        'name' => 'admin',
        'host' => (object)[
            'value' => 'core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/admin',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ],
    
    // site-module
    (object)[
        'name' => 'site-module',
        'host' => (object)[
            'value' => 'core.mim',
            'params' => (object)[],
            '_type' => 'text'
        ],
        'path' => (object)[
            'value' => '/:module',
            'params' => (object)[
                'module' => 'slug'
            ],
            '_type' => 'regex',
            '_value' => '!^\/(?<module>[a-zA-Z0-9-_]+)((\/.*))*$!'
        ],
        'errors' => (object)[
            '404' => (object)[
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
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
                '_handlers' => (object)[]
            ],
            '500' => (object)[
                '_handlers' => (object)[]
            ]
        ]
    ]
];