<?php
/**
 * CLI Provider
 * @package core
 * @version 0.0.2
 */

namespace Mim\Provider;

class Cli
{
    static function dInstall(){
        return date('Y-m-d H:i:s');
    }
}