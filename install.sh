#/bin/sh
REMOTE=https://raw.github.com/scarwu/Pointless/master/bin/poi
TARGET=/usr/local/bin/poi

wget $REMOTE -O /tmp/poi
chmod +x /tmp/poi
sudo mv /tmp/poi $TARGET