FROM php:8.3-apache AS base

RUN apt-get update \
 && apt-get upgrade -y \
 && apt-get install -y libapache2-mod-auth-openidc \
 && apt-get autoremove -y \
 && apt-get clean \
 && (apt-get distclean || rm -rf  /var/cache/apt/archives /var/lib/apt/lists/*) \
 && a2enmod rewrite \
 && mkdir -p /var/cache/apache2/mod_auth_openidc/oidc-sessions \
 && chown www-data:www-data /var/cache/apache2/mod_auth_openidc/oidc-sessions \
 && docker-php-ext-install pdo_mysql mysqli

COPY auth_openidc.conf /etc/apache2/mods-enabled/auth_openidc.conf

FROM base AS development

FROM base AS production

COPY . /var/www/html/online-exhibits
