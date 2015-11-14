FROM gliderlabs/alpine:latest

MAINTAINER Sergey Kibish <"sergey.kibish@gmail.com">

ENV NODE_VERSION 4.1.2

RUN apk add --update make gcc g++ python linux-headers paxctl libgcc libstdc++ git

RUN wget -c http://nodejs.org/dist/v"$NODE_VERSION"/node-v"$NODE_VERSION".tar.gz && \
  tar -xzf node-v"$NODE_VERSION".tar.gz && \
  cd node-v"$NODE_VERSION" && \
  ./configure --prefix=/usr && \
  make -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  make install && \
  paxctl -cm /usr/bin/node && \
  cd / && \
  if [ -x /usr/bin/npm ]; then \
    npm install -g npm && \
    find /usr/lib/node_modules/npm -name test -o -name .bin -type d | xargs rm -rf; \
  fi && \
  apk del curl make gcc g++ python linux-headers paxctl && \
    rm -rf /usr/share/man /tmp/* /var/cache/apk/* /root/.npm /root/.node-gyp \
    /usr/lib/node_modules/npm/man /usr/lib/node_modules/npm/doc /usr/lib/node_modules/npm/html \
    node-v4.1.2*

RUN npm install -g \
  grunt-cli \
  gulp \
  bower \
  browserify \
  babel

WORKDIR /app

VOLUME /app
