
FROM webdevops/php-nginx:8.4

SHELL ["/bin/bash", "-c"]

ENV PHP_MAX_EXECUTION_TIME=110

# Install system dependencies, including cron
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    cron \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -fsSL https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# Application directory
ARG LARAVEL_PATH=/production/shikaticket
ARG WEB_PATH=/production/shikaticket/public

ENV WEB_DOCUMENT_ROOT=${WEB_PATH}

WORKDIR ${LARAVEL_PATH}

# Copy application
COPY . ${LARAVEL_PATH}

# Create Laravel directories
RUN mkdir -p \
    bootstrap/cache \
    storage/logs \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies and build assets
RUN npm install && npm run build

# Configure Supervisor
COPY docker-configs/supervisord.conf \
    /opt/docker/etc/supervisor.d/laravel.conf

# Configure reservation expiration cron job
RUN echo '* * * * * application cd /production/shikaticket && /usr/local/bin/php artisan tickets:expire-reservations >> /proc/1/fd/1 2>&1' \
    > /etc/cron.d/tickets-expiration \
    && chmod 0644 /etc/cron.d/tickets-expiration

# Laravel permissions and storage link
RUN php artisan storage:link && \
    touch storage/logs/laravel.log && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache && \
    mkdir -p /var/log/supervisor /tmp && \
    chmod 755 /var/log/supervisor && \
    chown application:application /tmp

# Copy entrypoint
COPY entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Configure entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

EXPOSE 80

CMD ["supervisord"]
