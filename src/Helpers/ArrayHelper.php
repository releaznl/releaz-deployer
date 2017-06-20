<?php
/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 20-6-17
 * Time: 15:53
 */

namespace Deployer\Helpers;


class ArrayHelper
{

    public static function get($array, $keys)
    {
        $arrayFound = $array;
        foreach ($keys as $key) {
            if (array_key_exists($key, $arrayFound)) {
                $arrayFound = $arrayFound[$key];
            } else {
                return null;
            }
        }
        return $arrayFound;
    }

    public static function getFromArray($array, $key)
    {
        if (array_key_exists($array, $key)) {
            return $array[$key];
        }
        return null;
    }

}