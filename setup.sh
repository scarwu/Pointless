#/bin/sh

git submodule init
git submodule update

./build.php
./bin/poi gen
