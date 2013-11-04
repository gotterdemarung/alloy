#!/bin/bash

# Changing folder
PATH_SCRIPT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd ${PATH_SCRIPT}"/../"

echo
echo "Pulling changes from git"
echo "========================"
git pull

echo
echo "Updating composer"
echo "================="
composer update

echo
echo "Making symlinks to vendor/bin"
echo "============================="
ln -fs ../vendor/bin/phpcs bin/phpcs
ln -fs ../vendor/bin/phpmd bin/phpmd
ln -fs ../vendor/bin/phpunit bin/phpunit
echo "Done: phpcs, phpmd, phpunit"
echo
