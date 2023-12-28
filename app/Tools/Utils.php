<?php

namespace App\Tools;

class Utils
{
    public static function generateId(){
        return implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    }

    public static function apiKey(){
        return 'y' . implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 15), 6));
    }


}
