#!/bin/sh

cd `dirname $0`

# Install Composer Packages
composer install

# Install Node Packages
yarn install

