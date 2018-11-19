FROM alpine:3.8
MAINTAINER Scar Wu <xneriscool@gmail.com>
WORKDIR /home

ENV COMMAND /usr/local/bin/poi
ENV HOME /home

RUN apk --update add php7 php7-phar

COPY poi $COMMAND
COPY entrypoint.sh /entrypoint.sh

VOLUME ["/home"]
ENTRYPOINT ["/entrypoint.sh"]
CMD ["$COMMAND"]
