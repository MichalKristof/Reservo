#!/bin/bash

set -e

COMPOSE_FILE="docker-compose.testing.yaml"

if [ "$FORCE_BUILD" = "true" ]; then
  echo "🧱 Building containers before running tests..."
  docker compose -f $COMPOSE_FILE up -d --build --remove-orphans
else
  echo "🚀 Starting containers (without rebuild)..."
  docker compose -f $COMPOSE_FILE up -d
fi

echo "🔑 Generating app key..."
docker compose -f $COMPOSE_FILE exec php-fpm php artisan key:generate --env=testing

echo "🧼 Migrating database..."
docker compose -f $COMPOSE_FILE exec php-fpm php artisan migrate --env=testing

echo "🧪 Running tests..."
docker compose -f $COMPOSE_FILE exec php-fpm php artisan test --env=testing

echo "🧹 Stopping containers..."
docker compose -f $COMPOSE_FILE down
