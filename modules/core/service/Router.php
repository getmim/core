<?php
/**
 * Router service
 * @package core
 * @version 0.0.1
 */

namespace Mim\Service;

class Router extends \Mim\Service
{
    
    private $_params = [];
    
    public function getParam(string $name): ?string{
        return $this->_params[$name] ?? null;
    }
    
    public function setParam(string $name, string $value): void{
        $this->_params[$name] = $value;
    }

    public function asset(string $gate, string $path, int $version=0): ?string{
        $gates = \Mim\Library\Router::$all_gates;
        $used_gate = null;
        foreach($gates as $gt){
            if($gt->name != $gate)
                continue;
            $used_gate = $gt;
            break;
        }

        if(!$used_gate){
            trigger_error('Gate named `' . $gate . '` not found');
            return null;
        }

        $scheme = $this->config->secure ? 'https://' : 'http://';

        $result = $scheme . $used_gate->host->value;
        foreach($this->_params as $pk => $pv)
            $result = str_replace('!(:' . $pk . ')!', $pv, $result);

        $result.= '/theme/' . $gate . '/static/' . $path;
        if($version)
            $result.= '?v=' . $version;

        return $result;
    }
    
    public function to(string $name, array $params=[], array $query=[]): ?string{
        return '';
    }
}