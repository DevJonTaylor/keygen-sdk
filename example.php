<?php

include_once('./vendor/autoload.php');

$settings = \Keygen\Util\Settings::getInstance();

$object = $settings->toObject();

$settings->slug = 'jonnytest1101';

\Keygen\Util\Debug::displayVar($object, $settings);