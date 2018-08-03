#!/bin/sh

cd `dirname $0`/../

rm -rf src/sample/themes/*

cp -a themes/Classic/theme src/sample/themes/Classic
cp -a themes/Unique/theme src/sample/themes/Unique
