#!/bin/bash

# Run migrations
# The --force flag is needed for production environments
echo "Running database migrations..."
php artisan migrate --force

# Start Laravel scheduler in the background

echo "Starting Laravel scheduler..."
(
    while true; do
        php artisan schedule:run --no-interaction
        sleep 60
    done
) >> storage/logs/scheduler.log 2>&1 &
echo "Starting application..."
# Start the main process (supervisord)
exec "$@"