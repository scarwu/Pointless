#!/usr/bin/env bash

cd `dirname $0`

IMAGE_VERSION=`php -r 'include "../src/constant.php"; echo $constant["build"]["version"];'`
IMAGE_NAME="scarwu/pointless:latest"

case "$1" in
    build)
        cp ../bin/poi .
        docker build -t $IMAGE_NAME .
        ;;

    push)
        docker push $IMAGE_NAME
        ;;
    *)
        echo $"Usage: $0 {build|push}"
        exit 1
esac
