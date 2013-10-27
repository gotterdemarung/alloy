#!/bin/bash

# Code inspection
PATH_SCRIPT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd ${PATH_SCRIPT}"/../"

echo
echo "PHP MD (http://phpmd.org/) inspection"
echo "====================================="
vendor/bin/phpmd src/ text codesize,unusedcode,naming

echo
echo "PHP Code Sniffer inspection"
echo "==========================="
vendor/bin/phpcs --report=summary --standard=Zend src/

echo
echo "PHPUnit testing"
echo "==============="
vendor/bin/phpunit -c tests --process-isolation --strict
