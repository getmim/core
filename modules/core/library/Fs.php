<?php
/**
 * Filesystem 
 * @package core
 * @version 0.0.1
 */

namespace Mim\Library;

class Fs
{
    static function scan(string $path): ?array {
        if(!is_dir($path))
            return null;
        return array_values(array_diff(scandir($path), ['.', '..']));
    }
    
    static function mkdir(string $path): bool {
        if(is_dir($path))
            return true;
        return mkdir($path, 0777, true);
    }
    
    static function write(string $path, string $text): bool {
        $fname = basename($path);
        $dname = dirname($path);
        if(!Fs::mkdir($dname))
            return false;
        if(false === ($f = fopen($path, 'w')))
            return false;
        fwrite($f, $text);
        return fclose($f);
    }
}