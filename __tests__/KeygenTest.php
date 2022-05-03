<?php

use Keygen\Keygen;
use Keygen\Accounts\Token;
use Keygen\Util\Settings;

class KeygenTest extends PHPUnit\Framework\TestCase
{
    public function testGetSettings()
    {
        $this->assertInstanceOf('Settings', Keygen::getSettings());
    }

    public function testGetToken()
    {
        $this->assertInstanceOf('Token', Keygen::getToken());
    }

    public function testGetInstance()
    {
        $keygen = Keygen::getInstance();

        $this->assertInstanceOf('Keygen', $keygen);
    }
}