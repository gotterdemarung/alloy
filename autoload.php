<?php
// Main namesace
namespace Alloy;

// Autoloader for widgets
spl_autoload_register(
    function($className)
    {
        // Validating namespace
        if (substr($className, 0, strlen(__NAMESPACE__)) !== __NAMESPACE__) {
            return;
        }

        // Including folder
        $filename = __DIR__
            . '/src/'
            . str_replace('\\', '/', $className)
            . '.php';
        if (file_exists($filename)) {
            include $filename;
        }
    }
);