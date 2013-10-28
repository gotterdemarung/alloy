<?php

namespace Alloy\Tests;

// Registering own tests autoloader
spl_autoload_register(
    function($className)
    {
        if (substr($className, 0, strlen(__NAMESPACE__)) !== __NAMESPACE__) {
            return;
        }
        include __DIR__
            . '/unit/Alloy/'
            . str_replace(
                '\\',
                '/',
                substr($className, strlen(__NAMESPACE__))
            )
            . '.php';
    }
);

// Loading abstract classes
include_once __DIR__ . '/unit/AlloyTest.php';
include_once __DIR__ . '/unit/Alloy/Widgets/AbstractWidgetTest.php';

// Loading widgets autoloader
require __DIR__ . '/../autoload.php';
