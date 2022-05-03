<?php

use Keygen\Util\Settings;

class SettingsTest extends \PHPUnit\Framework\TestCase
{
    public array $data = array('slug' => 'test', 'pass' => 'qwer', 'token' => '', 'expires' => '');
    public string $configPath = __DIR__ . DIRECTORY_SEPARATOR . '.keygenrc';
    public Settings $settings;
    protected function getJson()
    {
        return json_decode(file_get_contents($this->configPath));
    }

    public function testSettingsIsReadingFileCorrectly()
    {
        $json = $this->getJson();
        $settings = Settings::getInstance($this->configPath);

        $expected = $json->slug;
        $actual = $settings->slug;

        $this->assertEquals($expected, $actual, "{$expected} || {$actual}");
    }

    public function testGettingStandardConfigFile()
    {
        $slug = Settings::getInstance($this->configPath)->slug;
        $this->settings = Settings::getInstance($this->configPath);
        $this->assertEquals($this->data['slug'], $slug);
    }

    public function testSavingToConfigFile()
    {
        $settings = Settings::getInstance($this->configPath);

        $settings->slug = 'jonnytest1101';
        $expected = $settings->slug;

        $actual = $this->getJson()->slug;
        $settings->slug = $this->data['slug'];

        $this->assertEquals($expected, $actual, "{$expected} || {$actual}");
    }

    public function testRestrictedSaves()
    {
        $key = 'shouldNotSave';
        Settings::getInstance($this->configPath)->{$key} = 'Will it?';

        $this->assertObjectNotHasAttribute($key, $this->getJson());
    }
}