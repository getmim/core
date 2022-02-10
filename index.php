<?php
/**
 * App starter
 * @package core
 * @version 1.8.0
 */

function static_self_server() {
    if (php_sapi_name() !== 'cli-server') {
        return false;
    }

    $uri = ltrim($_SERVER['REQUEST_URI']);
    $file_abs = __DIR__ . '/' . $uri;

    // rules to back to index.php:
    // - target not found
    // - target is dir
    // - target file start with .
    // - target file is php or phtml

    if (!file_exists($file_abs)) {
        return false;
    }

    if (is_dir($file_abs)) {
        return false;
    }

    $file_name = basename($file_abs);
    if (substr($file_name, 0, 1) == '.') {
        return false;
    }

    $file_ext = explode('.', $file_name);
    $file_ext = end($file_ext);
    $php_exts = ['php', 'phtml'];

    if (in_array($file_ext, $php_exts)) {
        return false;
    }

    return true;
}

if (static_self_server()) {
    return false;
}

define('BASEPATH', __DIR__);
require_once BASEPATH . '/modules/core/Mim.php';
Mim::init();
