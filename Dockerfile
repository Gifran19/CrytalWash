FROM php:8.2-apache

# Install dependencies and PHP extensions (cURL, pgsql, pdo_pgsql)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-install pdo_pgsql pgsql curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache ModRewrite
RUN a2enmod rewrite

# Change Apache DocumentRoot to point to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application source code
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
