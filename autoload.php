<?php
define('AUTOLOAD_LIBRARIES', array(
    'Keygen' => __DIR__ . DIRECTORY_SEPARATOR . 'src'
));
spl_autoload_register(function($class) {
    foreach(AUTOLOAD_LIBRARIES as $library => $path) {
        if(strpos($class, $library) !== false) {
            $classPath = str_replace(
                array('\\', $library),
                array(DIRECTORY_SEPARATOR, $path),
                $class . '.php');
            if(file_exists($classPath)) {
                include_once($classPath);
            }
        }
    }
});