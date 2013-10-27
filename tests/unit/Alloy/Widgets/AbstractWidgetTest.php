<?php

namespace PHPocket\Widgets\Tests;

use PHPocket\Widgets\WidgetInterface;

/**
 * Class WidgetTest
 * Base class for all widget tests
 *
 * @package PHPocket\Widgets\Tests
 */
abstract class AbstractWidgetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Reads rules from tpl file and applies to widget
     *
     * @param string $fileName
     * @param string $className
     *
     * @return void
     */
    public function assertTpl($fileName, $className)
    {
        // Empty filename check
        if (empty($fileName)) {
            $this->fail('Empty template filename provided');
            return;
        }
        // Extension
        if (substr($fileName, -5) !== '.json') {
            $fileName .= '.json';
        }

        // Reading content
        $content = null;
        if (file_exists($fileName) && is_readable($fileName)) {
            $content = @file_get_contents($fileName);
        } else if (file_exists(__FILE__ . $fileName)
            && is_readable(__FILE__ . $fileName)
        ){
            $content = @file_get_contents(__FILE__ . $fileName);
        }

        // Empty not read
        if (empty($content)) {
            $this->fail("Template {$fileName} not found");
            return;
        }

        // Decoding JSON
        $array = @json_decode($content, true);
        if (empty($array) || !is_array($array)) {
            $this->fail("Content in {$fileName} not valid");
        }


        // Running tests
        $reflection = new \ReflectionClass($className);
        $baseName = basename($fileName);
        foreach ($array as $index => $assert) {
            // Constructing object
            $instance = $reflection->newInstanceArgs($assert['in']);

            // HTML assertion
            if (isset($assert['html'])) {
                $this->assertSame(
                    $assert['html'],
                    $instance->getValue(WidgetInterface::HTML_FULL),
                    'HTML assert failed at ' . $index . ' in ' . $baseName
                );
            }

            // Plain assertion
            if (isset($assert['plain'])) {
                $this->assertSame(
                    $assert['plain'],
                    $instance->getValue(WidgetInterface::PLAINTEXT),
                    'Plaintext assert failed at ' . $index . ' in ' . $baseName
                );
            }

        }
    }


}