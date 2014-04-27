<?php

spl_autoload_register(function($class) {
    if (strpos($class, 'After\\') === 0) {
        $name = substr($class, strlen('After'));
        require __DIR__ . "/../lib" . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});
