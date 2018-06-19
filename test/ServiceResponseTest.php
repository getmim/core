<?php

use PHPUnit\Framework\TestCase;
use Mim\Service\Response;

class ServiceResponseTest extends TestCase
{
    protected function setUp(){
        require_once __DIR__ . '/mock/MimService.php';
        require_once dirname(__DIR__) . '/modules/core/service/Response.php';
    }
    
    
    public function contentProvider(){
        return [
            'single content' => [
                (object)[
                    'data' => [
                        ['lorem']
                    ],
                    'expect' => 'lorem'
                ]
            ],
            'multiple add content' => [
                (object)[
                    'data' => [
                        ['lorem'],
                        [' '],
                        ['ipsum']
                    ],
                    'expect' => 'lorem ipsum'
                ]
            ],
            'truncate content' => [
                (object)[
                    'data' => [
                        ['lorem'],
                        [' '],
                        ['ipsum', true]
                    ],
                    'expect' => 'ipsum'
                ]
            ]
        ];
    }
    
    /**
     * @dataProvider contentProvider
     * @group content
     */
    public function testAddContent($test){
        $res = new Response;
        foreach($test->data as $cont)
            call_user_func_array([$res, 'addContent'], $cont);
        
        ob_start();
        $res->send();
        ob_end_flush();
        
        $this->expectOutputString($test->expect);
    }
    
    /**
     * @dataProvider contentProvider
     * @group content
     */
    public function testGetContent($test){
        $res = new Response;
        foreach($test->data as $cont)
            call_user_func_array([$res, 'addContent'], $cont);
        
        $this->assertSame($res->getContent(), $test->expect);
    }
    
    /**
     * @dataProvider contentProvider
     * @group content
     */
     public function testRemoveContent($test){
        $res = new Response;
        foreach($test->data as $cont)
            call_user_func_array([$res, 'addContent'], $cont);
        
        $res->removeContent();
        
        $this->assertSame($res->getContent(), '');
    }
    

    public function cookieProvider(){
        return [
            'set cookie' => [
                (object)[
                    'data' => [
                        ['user', 'user']
                    ],
                    'output' => [
                        'Set-Cookie: user=user; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+7 days')) . ' GMT; Max-Age=604800'
                    ],
                    'get' => (object)[
                        'args' => ['user'],
                        'expect' => (object)['name'=>'user', 'value'=>'user', 'expires'=>604800]
                    ],
                    'remove' => (object)[
                        'args' => ['user'],
                        'expect' => []
                    ]
                ]
            ],
            'set cookie #2' => [
                (object)[
                    'data' => [
                        ['user', 'user2']
                    ],
                    'output' => [
                        'Set-Cookie: user=user2; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+7 days')) . ' GMT; Max-Age=604800'
                    ],
                    'get' => (object)[
                        'args' => ['user'],
                        'expect' => (object)['name'=>'user', 'value'=>'user2', 'expires'=>604800]
                    ],
                    'remove' => (object)[
                        'args' => ['user'],
                        'expect' => []
                    ]
                ]
            ],
            'set cookie with expiration' => [
                (object)[
                    'data' => [
                        ['user', 'user', 172800]
                    ],
                    'output' => [
                        'Set-Cookie: user=user; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+2 days')) . ' GMT; Max-Age=172800'
                    ],
                    'get' => (object)[
                        'args' => ['user'],
                        'expect' => (object)['name'=>'user', 'value'=>'user', 'expires'=>172800]
                    ],
                    'remove' => (object)[
                        'args' => [],
                        'expect' => []
                    ]
                ]
            ],
            'set cookie overwrite exists' => [
                (object)[
                    'data' => [
                        ['user', 'user2'],
                        ['user', 'user', 172800]
                    ],
                    'output' => [
                        'Set-Cookie: user=user; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+2 days')) . ' GMT; Max-Age=172800'
                    ],
                    'get' => (object)[
                        'args' => ['user'],
                        'expect' => (object)['name'=>'user', 'value'=>'user', 'expires'=>172800]
                    ],
                    'remove' => (object)[
                        'args' => [],
                        'expect' => []
                    ]
                ]
            ],
            'set cookie overwrite expiration' => [
                (object)[
                    'data' => [
                        ['user', 'user2', 172800],
                        ['user', 'user']
                    ],
                    'output' => [
                        'Set-Cookie: user=user; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+7 days')) . ' GMT; Max-Age=604800'
                    ],
                    'get' => (object)[
                        'args' => [],
                        'expect' => ['user' => (object)['name'=>'user', 'value'=>'user', 'expires'=>604800]]
                    ],
                    'remove' => (object)[
                        'args' => [],
                        'expect' => []
                    ]
                ]
            ],
            'set multiple cookie' => [
                (object)[
                    'data' => [
                        ['user1', 'user1'],
                        ['user2', 'user2']
                    ],
                    'output' => [
                        'Set-Cookie: user1=user1; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+7 days')) . ' GMT; Max-Age=604800',
                        'Set-Cookie: user2=user2; expires=' . gmdate('D, d-M-Y H:*:*', strtotime('+7 days')) . ' GMT; Max-Age=604800'
                    ],
                    'get' => (object)[
                        'args' => [],
                        'expect' => [
                            'user1' => (object)['name'=>'user1', 'value'=>'user1', 'expires'=>604800],
                            'user2' => (object)['name'=>'user2', 'value'=>'user2', 'expires'=>604800]
                        ]
                    ],
                    'remove' => (object)[
                        'args' => ['user1'],
                        'expect' => [
                            'user2' => (object)['name'=>'user2', 'value'=>'user2', 'expires'=>604800]
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * @dataProvider cookieProvider
     * @group cookie
     */
    public function testAddCookie($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addCookie'], $data);
        
        ob_start();
        $res->send();
        ob_end_flush();
        
        $match = true;
        
        $headers = xdebug_get_headers();
        foreach($test->output as $index => $test){
            $result = fnmatch($test, ($headers[$index]??''));
            if(!$result)
                $match = false;
        }
        
        $this->assertTrue($match);
    }
    
    /**
     * @dataProvider cookieProvider
     * @group cookie
     */
    public function testGetCookie($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addCookie'], $data);
        $result = call_user_func_array([$res, 'getCookie'], $test->get->args);
        
        $this->assertEquals($result, $test->get->expect);
    }
    
    /**
     * @dataProvider cookieProvider
     * @group cookie
     */
    public function testRemoveCookie($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addCookie'], $data);
        call_user_func_array([$res, 'removeCookie'], $test->remove->args);
        
        $result = $res->getCookie();
        $this->assertEquals($result, $test->remove->expect);
    }
    
    
    public function headerProvider(){
        return [
            'single header' => [
                (object)[
                    'data' => [
                        ['X-Info', 'info']
                    ],
                    'output' => [
                        'X-Info: info'
                    ],
                    'get' => (object)[
                        'args' => ['X-Info'],
                        'expect' => ['info']
                    ],
                    'remove' => (object)[
                        'args' => ['X-Info'],
                        'expect' => []
                    ]
                ]
            ],
            'multiple content' => [
                (object)[
                    'data' => [
                        ['X-Info', 'info'],
                        ['X-Info2', 'info2']
                    ],
                    'output' => [
                        'X-Info: info',
                        'X-Info2: info2'
                    ],
                    'get' => (object)[
                        'args' => [],
                        'expect' => [
                            'X-Info' => ['info'],
                            'X-Info2' => ['info2']
                        ]
                    ],
                    'remove' => (object)[
                        'args' => ['X-Info'],
                        'expect' => [
                            'X-Info2' => ['info2']
                        ]
                    ]
                ]
            ],
            'overwrite header' => [
                (object)[
                    'data' => [
                        ['X-Info', 'info'],
                        ['X-Info', 'info2', false]
                    ],
                    'output' => [
                        'X-Info: info2'
                    ],
                    'get' => (object)[
                        'args' => ['X-Info'],
                        'expect' => ['info2']
                    ],
                    'remove' => (object)[
                        'args' => [],
                        'expect' => []
                    ]
                ]
            ],
            'header multiple value' => [
                (object)[
                    'data' => [
                        ['X-Info', 'value'=>'info'],
                        ['X-Info', 'value'=>'info2']
                    ],
                    'output' => [
                        'X-Info: info',
                        'X-Info: info2'
                    ],
                    'get' => (object)[
                        'args' => ['X-Info'],
                        'expect' => ['info', 'info2']
                    ],
                    'remove' => (object)[
                        'args' => ['X-Info', 'info'],
                        'expect' => [
                            'X-Info' => ['info2']
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * @dataProvider headerProvider
     * @group header
     */
    public function testAddHeader($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addHeader'], $data);
        
        ob_start();
        $res->send();
        ob_end_flush();
        
        $headers = xdebug_get_headers();
        
        $this->assertEquals($headers, $test->output);
    }
    
    /**
     * @dataProvider headerProvider
     * @group header
     */
    public function testGetHeader($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addHeader'], $data);
        $result = call_user_func_array([$res, 'getHeader'], $test->get->args);
        
        $this->assertEquals($result, $test->get->expect);
    }
    
    /**
     * @dataProvider headerProvider
     * @group header
     */
    public function testRemoveHeader($test){
        $res = new Response;
        foreach($test->data as $data)
            call_user_func_array([$res, 'addHeader'], $data);
        call_user_func_array([$res, 'removeHeader'], $test->remove->args);
        
        $result = $res->getHeader();
        
        $this->assertEquals($result, $test->remove->expect);
    }
    
    
    public function cacheProvider(){
        return [
            [null, 0],
            [12, 12]
        ];
    }
    
    /**
     * @dataProvider cacheProvider
     * @group cache
     */
    public function testSetGetCache($cache, $expect){
        $res = new Response;
        if($cache)
            $res->setCache($cache);
        $this->assertSame($res->getCache(), $expect);
    }
    
    /**
     * @group cache
     */
    public function testRemoveCache(){
        $res = new Response;
        $res->setCache(12);
        $res->removeCache();
        $this->assertSame($res->getCache(), 0);
    }
    
    
    /**
     * @group status
     */
    public function testSetStatus(){
        $res = new Response;
        $res->setStatus(404);
        
        ob_start();
        $res->send();
        ob_end_flush();
        
        $status = http_response_code();
        
        $this->assertEquals($status, '404');
    }
    
    /**
     * @group status
     */
    public function testGetStatus(){
        $res = new Response;
        $res->setStatus(404);
        
        $this->assertSame($res->getStatus(), 404);
    }
    
    
    public function redirectStatusProvider(){
        return [
            [],
            [301],
            [302],
            [307]
        ];
    }
    
    /**
     * @dataProvider redirectStatusProvider
     * @group redirect
     */
    public function testRedirectStatus($status=null){
        $res = new Response;
        
        $args = ['https://www.google.com/'];
        if($status)
            $args[] = $status;
        
        ob_start();
        call_user_func_array([$res, 'redirect'], $args);
        ob_end_flush();
        
        $expect = $status ?? 302;
        
        $status = http_response_code();
        
        $this->assertSame($status, $expect);
    }
    
    /**
     * @group redirect
     */
    public function testRedirectContent(){
        $res = new Response;
        
        ob_start();
        $res->redirect('https://www.google.com/', 200);
        ob_end_flush();
        
        $this->expectOutputRegex('!<meta http-equiv="refresh" content="0; URL=\'https:\/\/www\.google\.com\/\'" \/>!');
    }
}