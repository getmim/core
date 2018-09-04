<?php
/**
 * Application manager
 * @package core
 * @version 0.0.1
 */

class Mim {

    static $app;
    static $_config;
    static $_service;
    
    /* object area */
    public function __construct(){
        self::$_service = [];
    }
    
    public function __get(string $name): object{
        if(isset(self::$_service[$name]))
            return self::$_service[$name];
            
        $services = self::$_config->service;
        if(!property_exists($services, $name))
            trigger_error('Service named `' . $name . '` not registered');
        return self::$_service[$name] = new $services->$name;
    }
    
    public function next(): void {
        Mim::$app->req->next();
    }
    
    /* static area */
    
    static function init(): void {
        self::_env();
        self::$_config = require_once BASEPATH . '/etc/cache/config.php';
        self::_autoload();
        self::_timezone();
        self::_error();
        self::$app = new Mim;

        // call the `core->ready` callback
        if(isset(self::$_config->callback)
            && isset(self::$_config->callback->core->ready)){
            
            $callbacks = self::$app->config->callback->core->ready;
            foreach($callbacks as $handler){
                $class = $handler->class;
                $method= $handler->method;

                $class::$method();
            }

        }

        self::$app->next();
    }
    
    private static function _autoload(): void {
        // load all files
        foreach(self::$_config->autoload->files as $file => $cond)
            $cond && require_once BASEPATH . '/' . $file;
        
        // load required classes
        spl_autoload_register(function($class){
            $file = Mim::$_config->autoload->classes->$class ?? null;
            if($file)
                include BASEPATH . '/' . $file;
        });

        // load commposer autoload
        $composer_autoload = BASEPATH . '/vendor/autoload.php';
        if(is_file($composer_autoload))
            include $composer_autoload;
    }
    
    private static function _env(): void {
        $env = file_get_contents(BASEPATH . '/etc/.env');
        $env = $env;
        
        define('ENVIRONMENT', $env);
        
        error_reporting(-1);
        if($env == 'development')
            ini_set('display_errors', 1);
        else
            ini_set('display_errors', 0);
    }
    
    private static function _error(){
        set_error_handler(function($no, $text, $file, $line){
            \Mim\Library\Logger::error($no, $text, $file, $line);
        });
        set_exception_handler(['Mim\Library\Logger', 'exceptioned']);
    }
    
    private static function _timezone(): void{
        date_default_timezone_set(self::$_config->timezone);
    }
}