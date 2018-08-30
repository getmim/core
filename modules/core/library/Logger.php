<?php
/**
 * Application logger
 * @package core
 * @version 0.0.1
 */

namespace Mim\Library;

class Logger {

    static $last_error;

    static function access($output){
        
    }
    
    static function error($no, $text, $file, $line, $trances=[]): void {
        self::$last_error = (object)[
            'file' => $file,
            'no'   => $no,
            'line' => $line,
            'text' => $text
        ];

        if(!$trances)
            $trances = debug_backtrace();
        self::$last_error->trace = $trances;

        $nl = PHP_EOL;
        
        $tx = date('Y-m-d H:i:s') . $nl;
        $tx.= $text . ' ( ' . $no . ' )' . $nl;
        $tx.= $file . ' ( ' . $line . ' )' . $nl;
        $tx.= str_repeat('-', 80) . $nl;
        
        $path = BASEPATH . '/etc/log/error/' . date('/Y/m/d/h/') . uniqid() . '.txt';
        Fs::write($path, $tx);

        if(\Mim::$app && \Mim::$app->req->gate){
            \Mim::$app->req->setProp('handler', \Mim::$app->req->gate->errors->{'500'}->_handlers);
            \Mim::$app->next();
        }
    }

    static function exceptioned($e){
        self::error(
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTrace()
        );
    }
}