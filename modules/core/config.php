<?php
/**
 * Mim Core
 * @package core
 * @version 0.0.3
 */

return [
    '__name' => 'core',
    '__version' => '0.0.3',
    '__git' => 'git@github.com:getphun/core.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'index.php' => ['install', 'update', 'remove'],
        '.gitignore' => ['remove'],
        'modules/.gitkeep' => ['remove'],
        'app' => ['install', 'remove'],
        'etc' => ['install', 'remove'],
        'modules/core' => ['install', 'update', 'remove']
    ],
    '__dependencies' => [
        'required' => [],
        'optional' => []
    ],
    '__inject' => [
        [
            'name' => 'name',
            'question' => 'Application name',
            'rule' => '!^.+$!'
        ],
        [
            'name' => 'version',
            'question' => 'Application version',
            'default' => '0.0.1',
            'rule' => '!^[0-9]+\.[0-9]+\.[0-9]+$!'
        ],
        [
            'name' => 'host',
            'question' => 'Application hostname, without scheme',
            'rule' => '!^[a-z0-9-\.]+$!'
        ],
        [
            'name' => 'timezone',
            'question' => 'Application timezone',
            'default' => 'Asia/Jakarta',
            'rule' => '!^.+$!'
        ],
        [
            'name' => 'install',
            'question' => 'Application installation time',
            'default' => [
                'class' => 'Mim\\Provider\\Cli',
                'method' => 'dInstall'
            ],
            'rule' => '!^.+$!'
        ],
        [
            'name' => 'secure',
            'question' => 'Use `https` scheme',
            'default' => true,
            'rule' => 'boolean'
        ]
    ],
    '__gitignore' => [
        'modules/*' => true,
        '!modules/.gitkeep' => true,
        
        'etc/cache/*' => true,
        '!etc/cache/.gitkeep' => true,
        
        'etc/cert/*' => true,
        '!etc/cert/.gitkeep' => true,
        
        'etc/config/development.php' => true,
        'etc/config/production.php' => true,
        'etc/config/test.php' => true,
        
        'etc/log/access/*' => true,
        '!etc/log/access/.gitkeep' => true,
        
        'etc/log/error/*' => true,
        '!etc/log/error/.gitkeep' => true,
        
        'etc/temp/*' => true,
        '!etc/temp/.gitkeep' => true
    ],
    'autoload' => [
        'classes' => [
            'Mim' => [
                'type' => 'file',
                'base' => 'modules/core/Mim.php'
            ],
            'Mim\\Controller' => [
                'type' => 'file',
                'base' => 'modules/core/system/Controller.php'
            ],
            'Mim\\Iface' => [
                'type' => 'file',
                'base' => 'modules/core/interface'
            ],
            'Mim\\Library' => [
                'type' => 'file',
                'base' => 'modules/core/library'
            ],
            'Mim\\Provider' => [
                'type' => 'file',
                'base' => 'modules/core/provider'
            ],
            'Mim\\Service' => [
                'type' => 'file',
                'base' => 'modules/core/system/Service.php',
                'children' => 'modules/core/service'
            ],
            'Mim\\Server' => [
                'type' => 'file',
                'base' => 'modules/core/server'
            ]
        ],
        'files' => [
            'modules/core/helper/global.php' => true
        ]
    ],
    'service' => [
        'config' => 'Mim\\Service\\Config',
        'req' => 'Mim\\Service\\Request',
        'router' => 'Mim\\Service\\Router',
        'res' => 'Mim\\Service\\Response'
    ],
    'server' => [
        'core' => [
            'PHP >= 7.2' => 'Mim\\Server\\PHP::version'
        ]
    ]
];