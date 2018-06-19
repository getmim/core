<?php
/**
 * Application logger
 * @package core
 * @version 0.0.1
 */

namespace Mim\Library;

class Logger {

    static function access($output){
        
    }
    
    static function error($no, $text, $file, $line): void {
        $nl = PHP_EOL;
        
        $tx = date('Y-m-d H:i:s') . $nl;
        $tx.= $text . ' ( ' . $no . ' )' . $nl;
        $tx.= $file . ' ( ' . $line . ' )' . $nl;
        $tx.= str_repeat('-', 80) . $nl;
        
        $path = BASEPATH . '/etc/log/error/' . date('/Y/m/d/h/') . uniqid() . '.txt';
        Fs::write($path, $tx);
        
        if(is_dev()){
            $trances = debug_backtrace();
            
            if(php_sapi_name() === 'cli'){
                echo $text . $nl;
                foreach($trances as $trace){
                    if(!isset($trace['file']))
                        continue;
                    echo ' - ' . $trace['file'] . ' ( ' . $trace['line'] . ' )' . $nl;
                }
            }else{
                echo $text . '<br>';
                echo '<ul>';
                foreach($trances as $trace){
                    if(!isset($trace['file']))
                        continue;
                    echo '<li>';
                    echo $trace['file'] . ' ( ' . $trace['line'] . ' )';
                    echo '</li>';
                }
                echo '</ul>';
            }
            exit;
        }
        
        if(\Mim::$app && \Mim::$app->req->gate){
            \Mim::$app->req->setProp('handler', \Mim::$app->req->gate->errors->{'500'}->_handlers);
            \Mim::$app->next();
        }
    }
}