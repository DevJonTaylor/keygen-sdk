<?php

namespace Keygen;

use Keygen\Accounts\Token;
use Exception;
use Keygen\Util\Debug;
use Keygen\Util\Settings;

class Keygen
{
    public static Keygen $self;


    public static function getToken(): Token
    {
        return Token::getInstance();
    }

    /**
     * @param string|null $pathToConfig
     * @return Settings
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public static function getSettings(string|null $pathToConfig = null): Settings
    {
        return Settings::getInstance($pathToConfig);
    }

    public static function getInstance(): Keygen
    {
        try
        {
            if(!self::$self) {

                // TODO:  Init Settings
                // TODO:  Init Token
                // TODO:  Init Keygen
            }

            // TODO:  return Keygen
        }

        catch(Exception $e)
        {
            Debug::displayVar($e);
        }
    }

    public function __construct(string $slug, string $pass)
    {

    }
}