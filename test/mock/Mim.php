<?php

class Mim{
    static $_config;
    
    static function init(){
        self::$_config = (object)[
            'autoload' => (object)[
                'classes' => (object)[
                    'Mim\\Service' => 'modules/core/system/Service.php'
                ]
            ],
            '_modules' => ['core']
        ];
    }
}