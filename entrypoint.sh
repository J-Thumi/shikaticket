#!/bin/bash

# Run migrations
# The --force flag is needed for production environments
php artisan migrate 

# Start the main process (supervisord)
exec "$@"