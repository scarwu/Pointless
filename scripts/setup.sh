#!/bin/bash

cd `dirname $0`/../

# Init & Update Git Sub-modules
git submodule init
git submodule update

# Install Composer Packages
composer install

