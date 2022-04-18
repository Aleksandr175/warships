ComposeFile="./docker-compose.yml"

up:
	docker-compose  --file $(ComposeFile) up -d
down:
	docker-compose  --file $(ComposeFile) down
php:
	docker-compose  --file $(ComposeFile) exec php81 bash
