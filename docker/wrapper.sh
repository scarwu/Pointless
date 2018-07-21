#!/usr/bin/env sh

export USER_ID=`id -u`
export GROUP_ID=`id -g`

docker run \
    --rm \
    --tty \
    --user "$USER_ID:$GROUP_ID" \
    --volume "$HOME:/home" \
    scarwu/pointless poi $1
