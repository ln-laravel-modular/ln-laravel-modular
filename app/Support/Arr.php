<?php

namespace App\Support;

class Arr extends \Illuminate\Support\Arr
{
    public static function transKey($array, $rules = [])
    {
        $keys = array_keys((array)$array);
        var_dump($keys);
        var_dump($rules);
        \preg_replace_array(array_keys($rules), array_values($rules), $array);
        // foreach ($array as $key => $value) {
        // }
        var_dump($array);
        return $array;
    }
}