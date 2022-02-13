#!/bin/bash

cd `dirname $0`/../

# Copy Editor
rm -rf src/sample/editor

cp -a subModules/PointlessEditor/dist src/sample/editor

# Copy Themes
rm -rf src/sample/themes/*

THEME_LIST=(
    "Classic"
)

for THEME in ${THEME_LIST[*]}
do
    cp -a subModules/PointlessTheme-$THEME/dist src/sample/themes/$THEME
done
