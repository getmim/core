<?php
/**
 * Router service
 * @package core
 * @version 0.0.1
 */

namespace Mim\Service;

class Router extends \Mim\Service{
    
    private $_params = [];
    
    public function getParam(string $name): ?string{
        return $this->_params[$name] ?? null;
    }
    
    public function setParam(string $name, string $value): void{
        $this->_params[$name] = $value;
    }
    
    public function to(string $name, array $params=[], array $query=[]): ?string{
    }
}