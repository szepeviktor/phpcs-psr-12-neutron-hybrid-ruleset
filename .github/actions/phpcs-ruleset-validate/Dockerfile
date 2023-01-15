# https://hub.docker.com/_/debian
FROM debian:bullseye-slim

LABEL org.opencontainers.image.title="PHP ruleset validator"

ENV LC_ALL="C.UTF-8"
ENV DEBIAN_FRONTEND="noninteractive"

RUN set -x \
    && apt-get update \
    && apt-get install -y wget libxml2-utils \
    && apt-get autoremove --purge -y \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/*

# https://www.w3.org/2012/04/XMLSchema.xsd
COPY XMLSchema.xsd /usr/local/share/xml/XMLSchema.xsd
COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
