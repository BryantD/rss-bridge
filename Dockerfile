FROM php:7.4.29-fpm

LABEL description="RSS-Bridge is a PHP project capable of generating RSS and Atom feeds for websites that don't have one."
LABEL repository="https://github.com/RSS-Bridge/rss-bridge"
LABEL website="https://github.com/RSS-Bridge/rss-bridge"

RUN apt-get update && \
    apt-get install --yes --no-install-recommends \
      nginx \
      zlib1g-dev \
      libzip-dev \
      libmemcached-dev && \
    docker-php-ext-install zip && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY ./config/nginx.conf /etc/nginx/sites-enabled/default

<<<<<<< HEAD
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
	&& apt-get --yes update \
	&& apt-get --yes --no-install-recommends install \
		zlib1g-dev \
	&& rm -rf /var/lib/apt/lists/* \
	&& sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
	&& sed -ri -e 's/(MinProtocol\s*=\s*)TLSv1\.2/\1None/' /etc/ssl/openssl.cnf \
	&& sed -ri -e 's/(CipherString\s*=\s*DEFAULT)@SECLEVEL=2/\1/' /etc/ssl/openssl.cnf

COPY --chown=www-data:www-data ./ /app/
=======
COPY --chown=www-data:www-data ./ /app/

EXPOSE 80

ENTRYPOINT ["/app/docker-entrypoint.sh"]
>>>>>>> fb091776c3d0ff2981ff486ae53f435259609280
