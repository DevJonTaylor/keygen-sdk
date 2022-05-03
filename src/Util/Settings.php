<?php
/**
 * User: Jon
 * Date: 2/26/2019
 */

namespace Keygen\Util;

const KEYGEN_CONFIG = __DIR__ .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '.keygenrc';

use Keygen\Exceptions\Exception;
use Keygen\Model\Traits\CanSaveTrait;
use Keygen\Model\Traits\GetterSetterTrait;

/**
 * @property string slug
 * @property string pass
 * @property string token
 * @property string expires
 */
class Settings
{
    /**
     * Holds the current instance that is being used.
     *
     * @var Settings|null
     * @since version 1.1
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected static Settings|null $self = null;

    /**
     * Holds the path to the configuration file if it is not passed in the argument.
     *
     * @var string
     * @since version 1.1
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected static string $pathToConfig = KEYGEN_CONFIG;

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

    /**
     * @param string|null $pathToConfig
     * @return Settings
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public static function getInstance(string|null $pathToConfig = null): Settings
    {
        if(!self::$self) {
            if($pathToConfig) self::$pathToConfig = $pathToConfig;
            self::$self = new Settings(self::$pathToConfig);
        }

        return self::$self;
    }

    use GetterSetterTrait;
    use CanSaveTrait;

    /**
     * @param string $pathToConfig
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __construct(string $pathToConfig)
    {
        try
        {
            $string = file_get_contents($pathToConfig);
            $this->data = json_decode($string, true);
            $this->add($this->data);
        }

        catch(Exception $ex)
        {
            Debug::displayVar($ex);
        }
    }


    /**
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected function save():void
    {
        $data = $this->removeRestricted($this->data);
        file_put_contents(self::$pathToConfig, json_encode((object) $data));
    }

    /**
     * Overriding to ensure if the property can be saved then to save the config file.
     *
     * @param string $name
     * @param string $value
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __set(string $name, string $value): void
    {
        $this->data[$name] = $value;
        if($this->canSave($name)) $this->save();
    }
}