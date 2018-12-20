FROM php:7.3-cli-alpine

ARG BUILD_DATE
ARG VCS_REF
ARG IMAGE_NAME
ARG DOCKER_REPO
ENV COMPOSER_ALLOW_SUPERUSER 1

LABEL Maintainer="Mhd Zaher Ghaibeh <z@zah.me>" \
      org.label-schema.name="zaherg/laracasts-downloader:latest" \
      org.label-schema.url="https://www.zah.me" \
      org.label-schema.build-date=$BUILD_DATE \
      org.label-schema.vcs-url="https://github.com/linuxjuggler/laracasts-downloader.git" \
      org.label-schema.vcs-ref=$VCS_REF \
      org.label-schema.schema-version="1.0.0"

COPY --from=composer:1.8 \
    /usr/bin/composer /usr/bin/composer

COPY docker-entrypoint.sh /docker-entrypoint.sh

ADD . /src/app
ADD .env.example /src/app/.env

WORKDIR /src/app

RUN apk update && apk add --no-cache tini && \
    composer global require hirak/prestissimo && \
    composer install --no-progress --no-dev --no-suggest --prefer-dist --optimize-autoloader

ENTRYPOINT ["/docker-entrypoint.sh"]


CMD ["laracasts"]