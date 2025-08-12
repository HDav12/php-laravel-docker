# ── PHP + Apache ───────────────────────────────────────────────────
FROM php:8.3-apache

# 1. MSSQL-drivers
RUN set -ex \
 && apt-get update \
 && apt-get install -y --no-install-recommends \
        gnupg2 curl apt-transport-https unixodbc-dev \
        build-essential autoconf pkg-config \
 && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
      | gpg --dearmor -o /usr/share/keyrings/microsoft.gpg \
 && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] \
      https://packages.microsoft.com/debian/12/prod bookworm main" \
      > /etc/apt/sources.list.d/mssql-release.list \
 && apt-get update \
 && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
 && pecl install sqlsrv pdo_sqlsrv \
 && echo "extension=sqlsrv.so"      > /usr/local/etc/php/conf.d/20-sqlsrv.ini \
 && echo "extension=pdo_sqlsrv.so" >> /usr/local/etc/php/conf.d/20-pdo_sqlsrv.ini \
 && apt-get purge -y build-essential autoconf pkg-config \
 && apt-get autoremove -y \
 && rm -rf /var/lib/apt/lists/*

# 2. Copy code
COPY . /var/www/html

# 3. (optioneel) DocumentRoot naar /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# PHP -> stderr (wordt opgepikt door Render Logs)
RUN printf "log_errors=On\nerror_reporting=E_ALL\ndisplay_errors=Off\nerror_log=/proc/self/fd/2\n" \
    > /usr/local/etc/php/conf.d/zzz-logging.ini

# Apache access/error logs -> stdout/stderr
RUN printf "ErrorLog /proc/self/fd/2\nCustomLog /proc/self/fd/1 combined\n" \
    > /etc/apache2/conf-available/docker-logs.conf \
 && a2enconf docker-logs

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
      /etc/apache2/sites-available/*.conf \
      /etc/apache2/apache2.conf


RUN sed -ri \
  -e 's!^\s*ErrorLog\s+.*!ErrorLog /proc/self/fd/2!g' \
  -e 's!^\s*CustomLog\s+.*!CustomLog /proc/self/fd/1 combined!g' \
  /etc/apache2/sites-available/000-default.conf

EXPOSE 80
# ENV SKIP_COMPOSER 1
# ENV WEBROOT /var/www/html/public
# ENV PHP_ERRORS_STDERR 1
# ENV RUN_SCRIPTS 1
# ENV REAL_IP_HEADER 1
# ENV APP_ENV production
# ENV APP_DEBUG false
# ENV LOG_CHANNEL stderr
# ENV COMPOSER_ALLOW_SUPERUSER 1

# CMD ["/start.sh"]
