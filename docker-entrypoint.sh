#!/bin/bash

# Wait for MySQL to be ready
until nc -z -v -w30 mysql 3306
do
  echo "Waiting for MySQL to start..."
  sleep 5
done

# Continue with Laravel entrypoint (e.g., run PHP-FPM)
exec "$@"
