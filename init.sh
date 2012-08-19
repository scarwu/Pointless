#/bin/sh

git submodule init > /dev/null
git submodule update

php ./Bin/poi init

