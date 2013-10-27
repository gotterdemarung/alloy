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
        // Excluding tests
        if (strpos($className, __NAMESPACE__ . '\\Tests') === 0) {
            return;
        }

        // Including folder
        include __DIR__
            . '/src/'
            . str_replace('\\', '/', $className)
            . '.php';
    }
);