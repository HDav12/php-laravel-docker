FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# ===== MSSQL drivers installeren =====
RUN set -ex \
  && apt-get update \
  && apt-get install -y --no-install-recommends \
       gnupg2 curl apt-transport-https unixodbc-dev \
       build-essential autoconf pkg-config \
  # Microsoft repo toevoegen (zonder apt-key!)
  && mkdir -p /etc/apt/keyrings \
  && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
       -o /etc/apt/keyrings/microsoft.asc \
  && echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/microsoft.asc] \
       https://packages.microsoft.com/debian/11/prod bullseye main" \
       > /etc/apt/sources.list.d/mssql-release.list \
  && apt-get update \
  && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
  # PECL-build
  && pecl install sqlsrv pdo_sqlsrv \
  # === EXTENSIES AANZETTEN (want docker-php-ext-enable bestaat niet) ===
  && echo "extension=sqlsrv.so"      > /usr/local/etc/php/conf.d/20-sqlsrv.ini \
  && echo "extension=pdo_sqlsrv.so" >> /usr/local/etc/php/conf.d/20-pdo_sqlsrv.ini \
  # opruimen
  && apt-get purge -y build-essential autoconf pkg-config \
  && apt-get autoremove -y \
  && rm -rf /var/lib/apt/lists/*

# ===== bestaande env-vars =====
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
