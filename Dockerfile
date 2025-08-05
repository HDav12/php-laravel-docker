FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# =====  MSSQL drivers  =====
RUN set -ex \
  && apt-get update \
  && apt-get install -y gnupg2 curl apt-transport-https unixodbc-dev \
        build-essential autoconf pkg-config \
  # Microsoft repo key + repo toevoegen
  && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
  && curl https://packages.microsoft.com/config/debian/12/prod.list \
       > /etc/apt/sources.list.d/mssql-release.list \
  && apt-get update \
  # ODBC-driver 18 installeren (ACCEPT_EULA is verplicht)
  && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
  # PECL-extensies bouwen
  && pecl install sqlsrv pdo_sqlsrv \
  && docker-php-ext-enable sqlsrv pdo_sqlsrv \
  # cleanup
  && apt-get purge -y build-essential autoconf pkg-config \
  && apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

# =====  bestaande ENV’s  =====
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
