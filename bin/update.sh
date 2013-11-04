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
