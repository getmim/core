<?php
/**
 * Application logger
 * @package core
 * @version 1.10.0
 */

namespace Mim\Library;

class Logger
{

    public static $last_error;

    public static function access()
    {
        $config = \Mim::$app->config;
        if (!$config->log || !$config->log->access) {
            return;
        }

        $handler = $config->log->access;
        $handler::addLog();
    }
    
    public static function error($no, $text, $file, $line, $trances = []): void
    {
        self::$last_error = (object)[
            'file' => $file,
            'no'   => $no,
            'line' => $line,
            'text' => $text
        ];

        if (!$trances) {
            $trances = debug_backtrace();
        }
        self::$last_error->trace = $trances;

        $nl = PHP_EOL;
        
        $tx = date('Y-m-d H:i:s') . $nl;
        $tx.= $text . ' ( ' . $no . ' )' . $nl;
        $tx.= $file . ' ( ' . $line . ' )' . $nl;
        $tx.= str_repeat('-', 80) . $nl;
        
        $path = BASEPATH . '/etc/log/error/' . date('/Y/m/d/h/') . uniqid() . '.txt';
        \Mim\Library\Fs::write($path, $tx);

        if (\Mim::$app && is_object((\Mim::$app->req??null)) && \Mim::$app->req->gate) {
            $handler = \Mim::$app->req->gate->errors->{'500'}->_handlers;
            \Mim::$app->req->setProp('handler', $handler);
            \Mim::$app->next();
        } else {
            echo '<pre>';
            echo $tx;
        }

        exit;
    }

    public static function exceptioned($e)
    {
        self::error(
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTrace()
        );
    }
}
