FROM php:7.0-cli
MAINTAINER Scar Wu <xneriscool@gmail.com>

WORKDIR /home
ENV HOME /home
VOLUME ["/home"]

RUN REMOTE="https://raw.github.com/scarwu/Pointless/master/bin/poi" \
    && TARGET="/usr/local/bin/poi" \
    && rm -rf /var/lib/apt/lists/* \
    && curl -fSL $REMOTE -o $TARGET \
    && chmod +x $TARGET

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

CMD ["poi"]
