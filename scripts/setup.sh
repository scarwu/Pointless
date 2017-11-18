#!/bin/sh

cd `dirname $0`/../

export ROOT=`pwd`

git submodule init
git submodule update

composer install
npm install

# Build Modernizor
cd ./node_modules/modernizr
npm install
./bin/modernizr -c lib/config-all.json
