<?php

namespace Kuhschnappel\FritzApi\Utility;

class Helper
{
    const GRAD_CELSIUS = '°C';

    public static function unhexlify($str)
    {
        $hexSplit = str_split($str, 2);
        foreach ($hexSplit as $hex) $bin = isset($bin) ? $bin . hex2bin($hex) : hex2bin($hex);
        return $bin;
    }

    public static function hash_pbkdf2_sha256($password, $saltHex, $iterations)
    {
        $salt = self::unhexlify($saltHex);
        return hash_pbkdf2("sha256", $password, $salt, $iterations);
    }

    public static function formatOutput($value, $targetFormat)
    {


        switch($targetFormat) {
            case Helper::GRAD_CELSIUS:
                return number_format($value,1,',', '.') . Helper::GRAD_CELSIUS;
                break;
        }
    }

}