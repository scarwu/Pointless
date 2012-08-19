#/bin/sh

git pull
git submodule init
git submodule update

git submodule foreach git pull
git submodule foreach git submodule init
git submodule foreach git submodule update

