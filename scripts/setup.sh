#!/bin/bash

cd `dirname $0`/../

# Init & Update Git Sub-modules
git submodule init
git submodule update

# Install Composer Packages
composer install

# Install Node Packages
yarn install

# Build Modernizor
cd ./node_modules/modernizr
npm install
./bin/modernizr -c lib/config-all.json
