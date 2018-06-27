<?php
/**
 * Global functions
 * @package core
 * @version 0.0.2
 */

function alt(...$args){
    foreach($args as $arg){
        if(!!$arg)
            return $arg;
    }
}

function autoload_class_exists(string $class): bool{
    return !!(Mim::$_config->autoload->classes->$class ?? false);
}

function deb(...$args): void{
    $is_cli = php_sapi_name() === 'cli';
    ob_start();
    
    if(!$is_cli)
        echo '<pre>';
    foreach($args as $arg){
        if(is_null($arg)){
            echo 'NULL';
        }elseif(is_bool($arg)){
            echo $arg ? 'TRUE' : 'FALSE';
        }else{
            $arg = print_r($arg, true);
            if(!$is_cli)
                $arg = hs($arg);
            echo $arg;
        }
        echo PHP_EOL;
    }
    if(!$is_cli)
        echo '</pre>';
    
    $ctx = ob_get_contents();
    ob_end_clean();
    
    echo $ctx;
    exit;
}

function group_by_prop(array $array, string $prop): array{
    $res = [];
    foreach($array as $arr){
        $key = $arr[$prop];
        if(!isset($res[$key]))
            $res[$key] = [];
        $res[$key][] = $arr;
    }
    
    return $res;
}

function hs(string $str): string{
    return htmlspecialchars($str, ENT_QUOTES);
}

function is_dev(): bool{
    return ENVIRONMENT === 'development';
}

function is_indexed_array(array $array): bool{
    return array_keys($array) === range(0, count($array) - 1);
}

function module_exists(string $name): bool{
    return in_array($name, Mim::$_config->_modules);
}

function object_replace(object $origin, object $new): object{
    $cloned = clone $origin;
    foreach($new as $key => $val)
        $cloned->$key = $val;
    return $cloned;
}

function objectify($arr){
    if(!is_array($arr))
        return $arr;
    foreach($arr as $key => $val)
        $arr[$key] = objectify($val);
    if(is_indexed_array($arr))
        return $arr;
    return (object)$arr;
}

function prop_as_key(array $array, string $prop): array{
    $res = [];
    foreach($array as $arr){
        $key = $arr[$prop];
        $res[$key] = $arr;
    }
    
    return $res;
}

function to_source($data, $space=0, $escape=true) {
    if(is_string($data)){
        if($escape)
            $data = addslashes($data);
        return "'" . $data . "'";
    }
    if(is_numeric($data))
        return $data;
    if(is_bool($data))
        return $data ? 'TRUE' : 'FALSE';
    if(is_null($data))
        return 'NULL';
    if(is_resource($data))
        return "'*RESOURCE*'";
    $is_array  = is_array($data);
    $is_object = is_object($data);
    
    if(!$is_object && !$is_array)
        return "'UNKNOW_DATA_TYPE'";
    
    $inner_space = $space + 4;
    $nl = PHP_EOL;
    
    $tx = $is_array ? '[' : '(object)[';
    
    if($is_array && is_indexed_array($data)){
        if(count($data) && (is_array($data[0]) || is_object($data[0]))){
            foreach($data as $ind => $val){
                $tx.= $nl . str_repeat(' ', $inner_space);
                $tx.= to_source($val, $inner_space, $escape) . ',';
            }
            $tx = chop($tx, ',');
            $tx.= $nl;
            $tx.= str_repeat(' ', $space);
        }else{
            foreach($data as $ind => $val)
                $tx.= to_source($val, $inner_space, $escape) . ',';
            $tx = chop($tx, ',');
        }
    }else{
        $prop_len = count((array)$data);
        if($prop_len){
            foreach($data as $key => $val){
                $tx.= $nl . str_repeat(' ', $inner_space);
                $tx.= to_source($key, $inner_space, $escape) . ' => ' . to_source($val, $inner_space, $escape);
                $tx.= ',';
            }
            $tx = chop($tx, ',');
            $tx.= $nl;
            $tx.= str_repeat(' ', $space);
        }
    }
    $tx.= ']';
    
    return $tx;
}