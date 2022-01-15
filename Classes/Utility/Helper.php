<?php

namespace Kuhschnappel\FritzApi\Utility;

class Helper
{
    public static function unhexlify($str){
        $hexSplit = str_split($str,2);
        foreach($hexSplit as $hex) $ret = isset($bin) ? $bin . hex2bin($hex) : hex2bin($hex);
        return $bin;
    }

}