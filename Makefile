up:
	docker compose up -d

down:
	docker compose down

bash:
	docker compose exec -it php-fpm bash

composer-install:
	docker compose exec -it php-fpm composer install

setup: up composer-install
