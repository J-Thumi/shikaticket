
#!/bin/bash

set -e

cd /production/shikaticket

echo "Running database migrations..."
php artisan migrate --force

echo "Starting cron..."
cron

echo "Starting application..."
exec "$@"
