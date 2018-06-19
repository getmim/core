<?php

use PHPUnit\Framework\TestCase;

class HelperGlobalTest extends TestCase
{

    protected function setUp(){
        require_once dirname(__DIR__) . '/modules/core/helper/global.php';
    }
    
    /**
     * @dataProvider altProvider
     */
    public function testAlt($source, $expect){
        $this->assertSame(alt(...$source), $expect);
    }
    public function altProvider(){
        return [
            'should get the #0' => [[1,2,3], 1],
            'should get the #1' => [[0,2,1], 2],
            'should get the #2' => [[0,false,1,2], 1],
            'should skip string 0' => [[false, '0',12], 12],
            'should skip empty string' => [['',12,2], 12]
        ];
    }
    
    /**
     * @dataProvider autoloadClassExistsProvider
     */
    public function testAutoloadClassExists($source, $expect){
        require_once 'mock/Mim.php';
        Mim::init();
        $this->assertSame(autoload_class_exists($source), $expect);
    }
    public function autoloadClassExistsProvider(){
        return [
            'should return true on exists' => ['Mim\\Service', true],
            'should return false on not exist' => ['Mim\\Unknown', false]
        ];
    }
    
    public function testGroupByProp(){
        $source = [
            ['type'=>'human', 'name'=>'jojo'],
            ['type'=>'human', 'name'=>'kiki'],
            ['type'=>'animal', 'name'=>'doggo']
        ];
        $expect = [
            'human' => [
                ['type'=>'human', 'name'=>'jojo'],
                ['type'=>'human', 'name'=>'kiki']
            ],
            'animal' => [
                ['type'=>'animal', 'name'=>'doggo']
            ]
        ];
        $this->assertSame(group_by_prop($source, 'type'), $expect);
    }
    
    public function testHs(){
        $source = "ta'wa";
        $expect = htmlspecialchars($source, ENT_QUOTES);
        $this->assertSame(hs($source), $expect);
    }
    
    /**
     * @dataProvider isDevProvider
     */
    public function testIsDev($source, $expect){
        if(defined('ENVIRONMENT'))
            uopz_undefine('ENVIRONMENT');
        define('ENVIRONMENT', $source);
        $this->assertSame(is_dev(), $expect);
    }
    public function isDevProvider(){
        return [
            ['development', true],
            ['production', false],
            ['test', false]
        ];
    }
    
    /**
     * @dataProvider IsIndexedArrayProvider
     */
    public function testIsIndexedArray($source, $expect){
        $this->assertSame(is_indexed_array($source), $expect);
    }
    public function IsIndexedArrayProvider(){
        return [
            [[0,1,2,3], true],
            [[1,2,3,4], true],
            [[0=>0,1=>1,2=>2,3=>3], true],
            [['0'=>0,1=>1,2=>2], true],
            [[0=>0,2=>2,1=>1,3=>3], false],
            [['a'=>1,2,3], false]
        ];
    }
    
    /**
     * @dataProvider moduleExistsProvider
     */
    public function testModuleExists($source, $expect){
        require_once 'mock/Mim.php';
        Mim::init();
        $this->assertSame(module_exists($source), $expect);
    }
    public function moduleExistsProvider(){
        return [
            ['core', true],
            ['lib-view', false]
        ];
    }
    
    public function testObjectReplace(){
        $source = (object)[
            'old0' => 'old0',
            'old1' => 'old2',
        ];
        $replacer = (object)[
            'new0' => 'new0',
            'old1' => 'old1'
        ];
        $expect = (object)[
            'old0' => 'old0',
            'old1' => 'old1',
            'new0' => 'new0'
        ];
        $this->assertEquals(object_replace($source, $replacer), $expect);
    }
    
    public function testPropAsKey(){
        $source = [
            ['id'=>1, 'name'=>'jojo'],
            ['id'=>2, 'name'=>'kiki'],
            ['id'=>3, 'name'=>'babay'],
            ['id'=>4, 'name'=>'koko']
        ];
        $expect = [
            1 => ['id'=>1, 'name'=>'jojo'],
            2 => ['id'=>2, 'name'=>'kiki'],
            3 => ['id'=>3, 'name'=>'babay'],
            4 => ['id'=>4, 'name'=>'koko']
        ];
        $this->assertSame(prop_as_key($source, 'id'), $expect);
    }
}