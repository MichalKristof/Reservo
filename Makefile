build:
	docker compose -f docker-compose.dev.yaml build --no-cache

up:
	docker compose -f docker-compose.dev.yaml up -d

bash:
	docker compose -f docker-compose.dev.yaml exec workspace bash

down:
	docker compose -f docker-compose.dev.yaml down

migrate:
	docker compose -f docker-compose.dev.yaml exec workspace php artisan migrate

migrate-fresh:
	docker compose -f docker-compose.dev.yaml exec workspace php artisan migrate:fresh --seed

test:
	./run-tests.sh
