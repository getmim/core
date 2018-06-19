<?php

use PHPUnit\Framework\TestCase;
use Mim\Service\Request;

class ServiceRequestGateTest extends TestCase
{
    protected function setUp(){
        if(defined('BASEPATH'))
            uopz_undefine('BASEPATH');
        define('BASEPATH', __DIR__ . '/ServiceRequestGateTest');
        require_once __DIR__ . '/mock/MimService.php';
        require_once dirname(__DIR__) . '/modules/core/service/Request.php';
    }
    
    /**
     * @dataProvider gateProvider
     */
    public function testGate($headers, $sapi, $expect){
        $unsets = ['HTTP_HOST', 'REQUEST_URI', 'argv'];
        foreach($unsets as $n){
            if(isset($_SERVER[$n]))
                unset($_SERVER[$n]);
        }
        foreach($headers as $key => $val)
            $_SERVER[$key] = $val;
        
        uopz_set_return('php_sapi_name', $sapi);
        
        $req = new Request('/gates.php', '/router.php');
        
        $this->assertEquals($req->gate->name, $expect);
    }
    public function gateProvider(){
        return [
            // site
            'site#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12'],
                'php-fpm',
                'site'
            ],
            
            // site-module
            'site-module#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/user?page=1&rpp=12'],
                'php-fpm',
                'site-module'
            ],
            'site-module#2' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/user/profile?page=1&rpp=12'],
                'php-fpm',
                'site-module'
            ],
            'site-module#3' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/adminer?page=1&rpp=12'],
                'php-fpm',
                'site-module'
            ],
            
            // admin
            'admin#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin?page=1&rpp=12'],
                'php-fpm',
                'admin'
            ],
            
            // admin-module
            'admin-module#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/user?page=1&rpp=12'],
                'php-fpm',
                'admin-module'
            ],
            'admin-module#2' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/user/account?page=1&rpp=12'],
                'php-fpm',
                'admin-module'
            ],
            
            // user-web
            'user-web#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12'],
                'php-fpm',
                'user-web'
            ],
            
            // user-web-module
            'user-web-module#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/user?page=1&rpp=12'],
                'php-fpm',
                'user-web-module'
            ],
            'user-web-module#2' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/user/account?page=1&rpp=12'],
                'php-fpm',
                'user-web-module'
            ],
            
            // user-web-admin
            'user-web-admin#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin?page=1&rpp=12'],
                'php-fpm',
                'user-web-admin'
            ],
            
            // user-web-admin-module
            'user-web-admin-module#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin/user?page=1&rpp=12'],
                'php-fpm',
                'user-web-admin-module'
            ],
            'user-web-admin-module#2' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin/user/account?page=1&rpp=12'],
                'php-fpm',
                'user-web-admin-module'
            ],
            
            // api
            'api#1' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12'],
                'php-fpm',
                'api'
            ],
            'api#2' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/account?page=1&rpp=12'],
                'php-fpm',
                'api'
            ],
            'api#3' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/user/account/awesome?page=1&rpp=12'],
                'php-fpm',
                'api'
            ],
            'api#4' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/medias/12/binary?page=1&rpp=12'],
                'php-fpm',
                'api'
            ],
            'api#5' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binarying?page=1&rpp=12'],
                'php-fpm',
                'api'
            ],
            
            // api-version
            'api-version#1' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12?page=1&rpp=12'],
                'php-fpm',
                'api-version'
            ],
            'api-version#2' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/?page=1&rpp=12'],
                'php-fpm',
                'api-version'
            ],
            'api-version#3' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/user?page=1&rpp=12'],
                'php-fpm',
                'api-version'
            ],
            'api-version#4' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/user/account/?page=1&rpp=12'],
                'php-fpm',
                'api-version'
            ],
            
            // api-version-media-binary
            'api-version-media-binary#1' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary?page=1&rpp=12'],
                'php-fpm',
                'api-version-media-binary'
            ],
            'api-version-media-binary#2' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary/?page=1&rpp=12'],
                'php-fpm',
                'api-version-media-binary'
            ],
            'api-version-media-binary#3' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary/upload?page=1&rpp=12'],
                'php-fpm',
                'api-version-media-binary'
            ],
            
            // cli
            'cli#1' => [
                [
                    'argv' => []
                ],
                'cli',
                'cli'
            ],
            
            // cli-module
            'cli-module#1' => [
                [
                    'argv' => [
                        'module'
                    ]
                ],
                'cli',
                'cli-module'
            ],
            'cli-module#2' => [
                [
                    'argv' => [
                        'module',
                        'install'
                    ]
                ],
                'cli',
                'cli-module'
            ],
            'cli-module#3' => [
                [
                    'argv' => [
                        'module',
                        'install',
                        'lib-curl'
                    ]
                ],
                'cli',
                'cli-module'
            ],
            
            // cli-helper
            'cli-helper#1' => [
                [
                    'argv' => [
                        'help'
                    ]
                ],
                'cli',
                'cli-helper'
            ],
            'cli-helper#2' => [
                [
                    'argv' => [
                        'app',
                        'install'
                    ]
                ],
                'cli',
                'cli-helper'
            ],
            'cli-helper#3' => [
                [
                    'argv' => [
                        'modules'
                    ]
                ],
                'cli',
                'cli-helper'
            ]
        ];
    }
    
    /**
     * @dataProvider paramProvider
     */
    public function testParam($headers, $sapi, $expect){
        $unsets = ['HTTP_HOST', 'REQUEST_URI', 'argv'];
        foreach($unsets as $n){
            if(isset($_SERVER[$n]))
                unset($_SERVER[$n]);
        }
        foreach($headers as $key => $val)
            $_SERVER[$key] = $val;
        
        uopz_set_return('php_sapi_name', $sapi);
        
        $req = new Request('/gates.php', '/router.php');
        
        $this->assertEquals($req->param, $expect);
    }
    public function paramProvider(){
        return [
            // cli-helper
            'cli-helper#1' => [
                [
                    'argv' => [
                        'help'
                    ]
                ],
                'cli',
                (object)[
                    'action' => 'help'
                ]
            ],
            'cli-helper#2' => [
                [
                    'argv' => [
                        'app',
                        'install'
                    ]
                ],
                'cli',
                (object)[
                    'action' => 'app'
                ]
            ],
            
            // api-version-media-binary
            'api-version-media-binary#1' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            'api-version-media-binary#2' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary/?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            'api-version-media-binary#3' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/media/12/binary/upload?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            
            // api-version
            'api-version#1' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            'api-version#2' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            'api-version#3' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/user?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            'api-version#4' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/12/user/account/?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'version' => 12
                ]
            ],
            
            // user-web-admin-module
            'user-web-admin-module#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin/user?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim',
                    'module' => 'user'
                ]
            ],
            'user-web-admin-module#2' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin/user/account?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim',
                    'module' => 'user'
                ]
            ],
            
            // user-web-admin
            'user-web-admin#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/admin?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim'
                ]
            ],
            
            // user-web-module
            'user-web-module#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/user?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim',
                    'module' => 'user'
                ]
            ],
            'user-web-module#2' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/user/account?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim',
                    'module' => 'user'
                ]
            ],
            
            // user-web
            'user-web#1' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'user' => 'mim'
                ]
            ],
            
            // admin-module
            'admin-module#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/user?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'module' => 'user'
                ]
            ],
            'admin-module#2' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/user/account?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'module' => 'user'
                ]
            ],
            
            // site-module
            'site-module#1' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/user?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'module' => 'user'
                ]
            ],
            'site-module#2' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/user/profile?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'module' => 'user'
                ]
            ],
            'site-module#3' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/adminer?page=1&rpp=12'],
                'php-fpm',
                (object)[
                    'module' => 'adminer'
                ]
            ]
        ];
    }
}