<?php

use PHPUnit\Framework\TestCase;
use Mim\Service\Request;

class ServiceRequestTest extends TestCase
{
    protected function setUp(){
        if(defined('BASEPATH'))
            uopz_undefine('BASEPATH');
        define('BASEPATH', __DIR__ . '/ServiceRequestTest');
        require_once __DIR__ . '/mock/MimService.php';
        require_once dirname(__DIR__) . '/modules/core/service/Request.php';
        
        uopz_set_return('php_sapi_name', 'fpm-fcgi');
    }
    
    /**
     * @dataProvider propsProvider
     */
    public function testProps($headers, $prop, $value){
        $unsets = [
            'HTTP_ACCEPT_ENCODING',
            'HTTP_ACCEPT_LANGUAGE',
            'HTTP_ACCEPT',
            'HTTP_USER_AGENT',
            'HTTP_HOST',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'REMOTE_ADDR',
            'HTTP_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'CONTENT_LENGTH',
            'REQUEST_METHOD',
            'REQUEST_SCHEME',
            'CONTENT_TYPE',
            'REQUEST_URI'
        ];
        foreach($unsets as $val)
            unset($_SERVER[$val]);
        foreach($headers as $name => $val)
            $_SERVER[$name] = $val;
        $req = new Request('/props/gates.php', '/props/router.php');
        
        $this->assertEquals($req->$prop, $value);
    }
    public function propsProvider(){
        return [
            'get accept header' => [
                [
                    'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
                    'HTTP_ACCEPT_LANGUAGE' => 'en-GB,en;q=0.5',
                    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                ],
                'accept',
                (object)[
                    'encoding' => ['gzip', 'deflate'],
                    'language' => ['en-GB', 'en;q=0.5'],
                    'type' => ['html','xhtml+xml','xml','*']
                ]
            ],
            'get user agent' => [
                [
                    'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'
                ],
                'agent',
                'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'
            ],
            'get hostname by HTTP_HOST' => [
                [
                    'HTTP_HOST' => 'core.mim'
                ],
                'host',
                'core.mim'
            ],
            'get ip by HTTP_CLIENT_IP' => [
                [
                    'HTTP_CLIENT_IP' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get ip by HTTP_X_FORWARDED_FOR' => [
                [
                    'HTTP_X_FORWARDED_FOR' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get ip by HTTP_X_FORWARDED' => [
                [
                    'HTTP_X_FORWARDED' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get ip by HTTP_FORWARDED_FOR' => [
                [
                    'HTTP_FORWARDED_FOR' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get ip by HTTP_FORWARDED' => [
                [
                    'HTTP_FORWARDED' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get ip by REMOTE_ADDR' => [
                [
                    'REMOTE_ADDR' => '192.168.12.33'
                ],
                'ip',
                '192.168.12.33'
            ],
            'get no ip' => [
                [],
                'ip',
                'UNKNOWN'
            ],
            'get content length' => [
                [
                    'CONTENT_LENGTH' => '332123'
                ],
                'length',
                '332123'
            ],
            'get empty content length' => [
                [],
                'length',
                '0'
            ],
            'get request method' => [
                [
                    'REQUEST_METHOD' => 'PATCH'
                ],
                'method',
                'PATCH'
            ],
            'get default request method' => [
                [],
                'method',
                'GET'
            ],
            'get request path' => [
                [
                    'REQUEST_URI' => '/awesome'
                ],
                'path',
                '/awesome'
            ],
            'get request path with suffix' => [
                [
                    'REQUEST_URI' => '/awesome/'
                ],
                'path',
                '/awesome'
            ],
            'get request path without query' => [
                [
                    'REQUEST_URI' => '/awesome?query=param'
                ],
                'path',
                '/awesome'
            ],
            'get request path without query and suffix' => [
                [
                    'REQUEST_URI' => '/awesome/?query=param'
                ],
                'path',
                '/awesome'
            ],
            'get default path' => [
                [],
                'path',
                '/'
            ],
            'get request scheme' => [
                [
                    'REQUEST_SCHEME' => 'http'
                ],
                'scheme',
                'http'
            ],
            'get request scheme https' => [
                [
                    'REQUEST_SCHEME' => 'https'
                ],
                'scheme',
                'https'
            ],
            'get request scheme ftp' => [
                [
                    'REQUEST_SCHEME' => 'ftp'
                ],
                'scheme',
                'ftp'
            ],
            'get content type' => [
                [
                    'CONTENT_TYPE' => 'application/json;charset=utf8'
                ],
                'type',
                'json'
            ],
            'get https url' => [
                [
                    'REQUEST_SCHEME' => 'https',
                    'HTTP_HOST' => 'core.mim',
                    'REQUEST_URI' => '/'
                ],
                'url',
                'https://core.mim/'
            ],
            'get url with with path' => [
                [
                    'REQUEST_SCHEME' => 'https',
                    'HTTP_HOST' => 'core.mim',
                    'REQUEST_URI' => '/awesome/'
                ],
                'url',
                'https://core.mim/awesome/'
            ],
            'get url with query string' => [
                [
                    'REQUEST_SCHEME' => 'https',
                    'HTTP_HOST' => 'core.mim',
                    'REQUEST_URI' => '/awesome?something=awesome'
                ],
                'url',
                'https://core.mim/awesome?something=awesome'
            ]
        ];
    }
    
    /**
     * @dataProvider getBodyProdiver
     */
    public function testGetBody($input, $content_type, $name, $default, $expect){
        uopz_set_return('file_get_contents', $input);
        $_SERVER['CONTENT_TYPE'] = $content_type;
        
        $req = new Request('/props/gates.php', '/props/router.php');
        
        $this->assertEquals($req->getBody($name, $default), $expect);
    }
    public function getBodyProdiver(){
        return [
            'return exists prop' => ['{"name": "mim", "version":"0.0.1"}', 'application/json;chartset=utf8', 'name', null, 'mim'],
            'return default' => ['{"name": "mim", "version":"0.0.1"}', 'application/json;chartset=utf8', 'undef', 'lock', 'lock'],
            'return all object' => ['{"name": "mim", "version":"0.0.1"}', 'application/json;chartset=utf8', null, null, (object)['name'=>'mim', 'version'=>'0.0.1']],
            'return all input' => ['{"name": "mim", "version":"0.0.1"}', '', null, null, '{"name": "mim", "version":"0.0.1"}'],
            'invalid json' => ['{"name": "mim", "version":"0.0.1}', 'application/json;chartset=utf8', 'name', 'def', 'def'],
        ];
    }
    
    /**
     * @dataProvider getCookieProvider
     */
    public function testGetCookie($getname, $getdeff, $expect, $name, $value){
        if($name)
            $_COOKIE[$name] = $value;
        $req = new Request('/props/gates.php', '/props/router.php');
        
        $this->assertSame($req->getCookie($getname, $getdeff), $expect);
    }
    public function getCookieProvider(){
        return [
            ['session', null, null, '', ''],
            ['session', 'awesome', 'awesome', '', ''],
            ['session', null, 'awesome', 'session', 'awesome']
        ];
    }
    
    /**
     * @dataProvider getFileProvider
     */
    public function testGetFile($name, $data, $expect){
        if($data)
            $_FILES[$name] = $data;
        $req = new Request('/props/gates.php', '/props/router.php');
        
        $this->assertEquals($req->getFile($name), $expect);
    }
    public function getFileProvider(){
        $file = [
            'name' => 'img.jpg',
            'type' => 'image/jpg',
            'size' => 12332,
            'tmp_name' => '/tmp/img.jpg',
            'error' => 0
        ];
        
        return [
            ['file', $file, $file],
            ['img', '', null]
        ];
    }
    
    /**
     * @dataProvider getPostProvider
     */
    public function testGetPost($name, $preset, $def, $expect){
        $unsets = ['name', 'uname', 'password'];
        foreach($unsets as $n){
            if(isset($_POST[$n]))
                unset($_POST[$n]);
        }
        if($preset){
            foreach($preset as $n => $v)
                $_POST[$n] = $v;
        }
        
        $req = new Request('/props/gates.php', '/props/router.php');
        $this->assertEquals($req->getPost($name, $def), $expect);
    }
    public function getPostProvider(){
        return [
            ['name', ['name' =>'mim', 'password'=>'1234'], null, 'mim'],
            ['name', ['uname'=>'mim'], 'mex', 'mex'],
            ['name', ['uname'=>'mim'], null, null],
            [null,   ['name'=>'mim', 'password'=>'1234'], null, ['name'=>'mim', 'password'=>'1234']]
        ];
    }
    
    /**
     * @dataProvider getQueryProvider
     */
    public function testGetQuery($name, $preset, $def, $expect){
        $unsets = ['page', 'rpp', 'q'];
        foreach($unsets as $n){
            if(isset($_GET[$n]))
                unset($_GET[$n]);
        }
        if($preset){
            foreach($preset as $n => $v)
                $_GET[$n] = $v;
        }
        
        $req = new Request('/props/gates.php', '/props/router.php');
        $this->assertEquals($req->getQuery($name, $def), $expect);
    }
    public function getQueryProvider(){
        return [
            ['page',  ['page' =>1, 'rpp'=>12], null, 1],
            ['pages', ['page' =>1, 'rpp'=>12], 13, 13],
            ['pages', ['page' =>1, 'rpp'=>12], null, null],
            [null,    ['page' =>1, 'rpp'=>12], null, ['page' =>1, 'rpp'=>12]]
        ];
    }
    
    /**
     * @dataProvider getServerProvider
     */
    public function testGetServer($name, $def, $expect){
        $_SERVER['HTTP_HOST'] = 'core.mim';
        $req = new Request('/props/gates.php', '/props/router.php');
        $this->assertEquals($req->getServer($name, $def), $expect);
    }
    public function getServerProvider(){
        return [
            ['HTTP_HOST', '', 'core.mim'],
            ['HTTP_HOSTS', 'core.mim.com', 'core.mim.com'],
            ['HTTP_HOSTS', null, null]
        ];
    }
    
    /**
     * @dataProvider isAjaxProvider
     */
    public function testIsAjax($value, $expect){
        $_SERVER['HTTP_X_REQUESTED_WITH'] = $value;
        $req = new Request('/props/gates.php', '/props/router.php');
        $this->assertEquals($req->isAjax(), $expect);
    }
    public function isAjaxProvider(){
        return [
            ['xmlhttprequest', true],
            ['', false]
        ];
    }
    
    /**
     * @dataProvider isCLIProvider
     */
    public function testIsCLI($value, $expect){
        uopz_set_return('php_sapi_name', $value);
        $req = new Request('/props/gates.php', '/props/router.php');
        $this->assertEquals($req->isCLI(), $expect);
    }
    public function isCLIProvider(){
        return [
            ['cli', true],
            ['phpfpm', false]
        ];
    }
    
    /**
     * @dataProvider setPropProvider
     */
    public function testSetProp($getname, $expect, $setname, $setvalue){
        $req = new Request('/props/gates.php', '/props/router.php');
        if($setname)
            $req->setProp($setname, $setvalue);
        $this->assertEquals($req->$getname, $expect);
    }
    public function setPropProvider(){
        return [
            ['lorem', null, '', ''],
            ['lorem', 'ipsum', 'lorem', 'ipsum']
        ];
    }
    
    public function testSetPropReadonly(){
        $that = $this;
        set_error_handler(function($no, $msg) use($that){
            $that->assertSame($msg, 'Property agent is readonly');
        });
        
        $req = new Request('/props/gates.php', '/props/router.php');
        $req->setProp('agent', 'loremify');
    }
}