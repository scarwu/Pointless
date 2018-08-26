#!/bin/bash

cd `dirname $0`/../

rm -rf src/sample/themes/*

THEME_LIST=(
    "Classic"
    "Unique"
)

for THEME in ${THEME_LIST[*]}
do
    cp -a subModules/PointlessTheme-$THEME/theme src/sample/themes/$THEME
done
