<?php
/**
 * User: Jon
 * Date: 2/26/2019
 */

namespace Keygen\Util;


class Settings
{
    protected static $settings = array();

    public static function set($name, $value = null)
    {
        $nameType = gettype($name);
        switch($nameType) {
            case 'object':
                self::set(get_object_vars($name));
                break;
            case 'array':
                foreach($name as $n => $v) {
                    self::set($n, $v);
                }
                break;
            case 'string':
            case 'integer':
                $valueType = gettype($value);
                switch($valueType) {
                    case 'string':
                    case 'integer':
                        self::$settings[$name] = $value;
                        break;
                }
                break;
        }
    }

    public static function get($name, $default = null)
    {
        return isset(self::$settings[$name]) ? self::$settings[$name] : $default;
    }
}