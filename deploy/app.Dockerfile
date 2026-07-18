# PHP 8.3-FPM app node. Two of these run behind Caddy.
# The application code is bind-mounted at runtime (see docker-compose.yml), so
# this image only carries the runtime + extensions.
FROM php:8.3-fpm-alpine

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli opcache; \
	pecl install redis; \
	docker-php-ext-enable redis; \
	apk del .build-deps

COPY deploy/php/app.ini /usr/local/etc/php/conf.d/zz-foodmart.ini

WORKDIR /var/www/html
