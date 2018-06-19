<?php

use PHPUnit\Framework\TestCase;

use Mim\Library\Fs;

class LibraryFsTest extends TestCase
{
    protected function setUp(){
        require_once dirname(__DIR__) . '/modules/core/library/Fs.php';
    }
    
    public function testScan(){
        $expect = ['file1.txt', 'file2.txt'];
        $dir = __DIR__ . '/LibraryFsTest/scan';
        $this->assertSame(Fs::scan($dir), $expect);
    }
    
    public function testMkdir(){
        $dir = __DIR__ . '/LibraryFsTest/mkdir/1/2/3';
        
        $direx = $dir;
        for($i=0; $i<3; $i++){
            if(is_dir($direx))
                rmdir($direx);
            $direx = dirname($direx);
        }
        
        Fs::mkdir($dir);
        $this->assertDirectoryExists($dir);
        
        $direx = $dir;
        for($i=0; $i<3; $i++){
            if(is_dir($direx))
                rmdir($direx);
            $direx = dirname($direx);
        }
    }
    
    /**
     * @dataProvider writeProvider
     */
    public function testWrite($file){
        if(is_file($file))
            unlink($file);
        if(strstr($file, '/1/2/3/')){
            $direx = dirname($file);
            for($i=0; $i<3; $i++){
                if(is_dir($direx))
                    rmdir($direx);
                $direx = dirname($direx);
            }
        }
        
        Fs::write($file, 'content');
        $this->assertStringEqualsFile($file, 'content');
        
        if(is_file($file))
            unlink($file);
        if(strstr($file, '/1/2/3/')){
            $direx = dirname($file);
            for($i=0; $i<3; $i++){
                if(is_dir($direx))
                    rmdir($direx);
                $direx = dirname($direx);
            }
        }
    }
    public function writeProvider(){
        return [
            [__DIR__ . '/LibraryFsTest/write/file.txt'],
            [__DIR__ . '/LibraryFsTest/write/1/2/3/file.txt']
        ];
    }
}