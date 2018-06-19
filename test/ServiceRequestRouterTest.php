<?php

use PHPUnit\Framework\TestCase;
use Mim\Service\Request;

class ServiceRequestRouterTest extends TestCase
{
    protected function setUp(){
        if(defined('BASEPATH'))
            uopz_undefine('BASEPATH');
        define('BASEPATH', __DIR__ . '/ServiceRequestRouterTest');
        require_once __DIR__ . '/mock/MimService.php';
        require_once dirname(__DIR__) . '/modules/core/service/Request.php';
    }
    
    /**
     * @dataProvider routeProvider
     */
    public function testRoute($headers, $sapi, $expect){
        $unsets = ['HTTP_HOST', 'REQUEST_URI', 'argv'];
        foreach($unsets as $n){
            if(isset($_SERVER[$n]))
                unset($_SERVER[$n]);
        }
        foreach($headers as $key => $val)
            $_SERVER[$key] = $val;
            
        uopz_set_return('php_sapi_name', $sapi);
        
        $req = new Request('/gates.php', '/router.php');
        
        $this->assertEquals($req->route->name, $expect);
    }
    public function routeProvider(){
        return [
            'cliModuleIndex' => [
                ['argv'=>['module']],
                'cli',
                'cliModuleIndex'
            ],
            'cliModuleInfo' => [
                ['argv'=>['module', 'lib-curl']],
                'cli',
                'cliModuleInfo'
            ],
            'cli404' => [
                ['argv'=>['module', 'lib-curl', 'awesome']],
                'cli',
                '404'
            ],
            'cliInfo' => [
                ['argv'=>['help']],
                'cli',
                'cliInfo'
            ],
            'cliInfoSingle' => [
                ['argv'=>['help', 'module']],
                'cli',
                'cliInfoSingle'
            ],
            
            'siteHome' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'siteHome'
            ],
            'site404' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/not-found-page?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                '404'
            ],
            'siteHomeCreate' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'POST'],
                'php-fpm',
                'siteHomeCreate'
            ],
            'sitePageSingle' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/page/about-us?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'sitePageSingle'
            ],
            'adminModuleIndex' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'adminModuleIndex'
            ],
            'adminModuleIndexSuffix' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'adminModuleIndex'
            ],
            'adminModuleIndexPlain' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'adminModuleIndex'
            ],
            'adminModuleIndexPlainSuffix' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'adminModuleIndex'
            ],
            'adminModuleIndexInfo' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/statistic?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'adminModuleIndexInfo'
            ],
            'userWebHome' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'userWebHome'
            ],
            'userWebPostSingle' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/post/first-post?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'userWebPostSingle'
            ],
            'apiVersionHome' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'apiVersionHome'
            ],
            'apiVersionInfoIndex' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1/info?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'apiVersionInfoIndex'
            ],
            'apiVersionInfoSingle' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1/info/user?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                'apiVersionInfoSingle'
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
            'cliModuleIndex' => [
                ['argv'=>['module']],
                'cli',
                (object)[]
            ],
            'cliModuleInfo' => [
                ['argv'=>['module', 'lib-curl']],
                'cli',
                (object)['name'=>'lib-curl']
            ],
            'cliInfo' => [
                ['argv'=>['help']],
                'cli',
                (object)['action'=>'help']
            ],
            'cliInfoSingle' => [
                ['argv'=>['help', 'module']],
                'cli',
                (object)['action'=>'help', 'info'=>'module']
            ],
            
            'siteHome' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)[]
            ],
            'siteHomeCreate' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'POST'],
                'php-fpm',
                (object)[]
            ],
            'sitePageSingle' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/page/about-us?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['slug'=>'about-us']
            ],
            'adminModuleIndex' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['module'=>'static-page']
            ],
            'adminModuleIndexSuffix' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['module'=>'static-page']
            ],
            'adminModuleIndexPlain' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['module'=>'static-page']
            ],
            'adminModuleIndexPlainSuffix' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['module'=>'static-page']
            ],
            'adminModuleIndexInfo' => [
                ['HTTP_HOST'=>'core.mim', 'REQUEST_URI'=>'/admin/static-page/statistic?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['module'=>'static-page', 'info'=>'statistic']
            ],
            'userWebHome' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['user'=>'mim']
            ],
            'userWebPostSingle' => [
                ['HTTP_HOST'=>'mim.core.mim', 'REQUEST_URI'=>'/post/first-post?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['user'=>'mim','slug'=>'first-post']
            ],
            'apiVersionHome' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['version'=>1]
            ],
            'apiVersionInfoIndex' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1/info?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['version'=>1]
            ],
            'apiVersionInfoSingle' => [
                ['HTTP_HOST'=>'api.core.mim', 'REQUEST_URI'=>'/1/info/user?page=1&rpp=12', 'REQUEST_METHOD'=>'GET'],
                'php-fpm',
                (object)['version'=>1,'info'=>'user']
            ]
        ];
    }
}