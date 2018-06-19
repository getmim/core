<?php
/**
 * Mim Core
 * @package core
 * @version 0.0.1
 */

return [
    '__name' => 'core',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getphun/core.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'index.php' => ['install', 'update', 'remove'],
        'app' => ['install', 'remove'],
        'etc' => ['install', 'remove'],
        'modules' => ['install', 'remove']
    ],
    '__dependencies' => [
        'required' => [],
        'optional' => []
    ],
    '__inject' => [
        'name' => [
            '__info' => 'Application name',
            '__type' => 'text'
        ],
        'version' => [
            '__info' => 'Application version',
            '__type' => 'version',
            '__default' => '0.0.1'
        ],
        'host' => [
            '__info' => 'Application hostname ( without scheme )',
            '__type' => 'host',
            '__default' => 'Mim\\Provider\\Cli::dHost'
        ],
        'timezone' => [
            '__info' => 'Application default timezone ( default Asia/Jakarta )',
            '__type' => 'Mim\\Provider\\Cli::vTimezone',
            '__default' => 'Asia/Jakarta'
        ],
        'install' => [
            '__info' => 'Application installation time',
            '__type' => 'text',
            '__default' => 'Mim\\Provider\\Cli::dInstall'
        ],
        'secure' => [
            '__info' => 'Is application use https',
            '__type' => 'boolean',
            '__default' => false
        ],
        'gates' => [
            '$name' => [
                '__info' => 'Gate name',
                '__type' => 'text',
                '__value' => [
                    'host' => [
                        '__info' => 'Gate host',
                        '__type' => 'Mim\\Provider\\Cli::vGateHost',
                        '__default' => 'Mim\\Provider\\Cli::dGateHost'
                    ],
                    'path' => [
                        '__info' => 'Gate path',
                        '__type' => 'Mim\\Provider\\Cli::vGatePath',
                        '__default' => '/'
                    ]
                ]
            ]
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
        'etc/log/access' => true,
        '!etc/log/access/.gitkeep' => true,
        'etc/log/error' => true,
        '!etc/log/error/.gitkeep' => true,
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