#!/usr/bin/env bash

export IMAGE_NAME="scarwu/pointless"

cd `dirname $0`/../src/docker

case $1 in
    "build")
        docker build -t $IMAGE_NAME .
    ;;
    "push")
        docker push $IMAGE_NAME
    ;;
    *)
        echo "command:"
        echo "    build"
        echo "    push"
        exit 1
    ;;
esac