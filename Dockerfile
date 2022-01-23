FROM alpine:3.12 as build
#ARG TARGETPLATFORM
ARG VEXIM_VERSION

RUN apk update && apk add curl

WORKDIR /app

RUN curl -SLO https://github.com/vexim/vexim2/archive/v${VEXIM_VERSION}.tar.gz

RUN tar -xzf *.tar.gz -C . \
  && mv ./vexim2-${VEXIM_VERSION} ./vexim


FROM php:7.4-apache
ARG VEXIM_UID=90
ARG VEXIM_GID=90

RUN apt-get update && apt-get install -y \
        libc-client-dev libkrb5-dev \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap mysqli pdo pdo_mysql gettext

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENV VEXIM_SQL_SERVER="localhost" \
  VEXIM_SQL_TYPE="mysql" \
  VEXIM_SQL_DB="vexim" \
  VEXIM_SQL_USER="vexim" \
  VEXIM_SQL_PASS="CHANGE" \
  VEXIM_CRYPTSCHEME="sha512" \
  VEXIM_MAILROOT="/var/vmail/" \
  VEXIM_UID="${VEXIM_UID}" \
  VEXIM_GID="${VEXIM_GID}"

RUN groupadd -g ${VEXIM_UID} vexim \
   && useradd -u ${VEXIM_UID} -g vexim vexim

COPY --from=build /app/vexim/vexim/ /var/www/html/
COPY ./variables.php /var/www/html/config/variables.php