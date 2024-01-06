include .env
export

DC := docker-compose exec
APP := $(DC) app
APP_SU := $(DC) --user=root app
NODE := $(DC) node npm
ARTISAN := $(APP) php artisan
MARIADB := $(DC) -T mariadb
CURRENT_UID := $(shell id -u)

start:
	@docker-compose up -d

stop:
	@docker-compose stop

restart: stop start

env:
	cp ./.env.example ./.env

ssh:
	@$(APP) bash

ssh-su:
	@$(APP_SU) bash

ssh-node:
	$(DC) node bash

keygen:
	@$(ARTISAN) key:generate

cache-clear:
	@$(ARTISAN) cache:clear
	@$(ARTISAN) view:clear

clear:
	@$(ARTISAN) optimize:clear

truncate:
	@$(ARTISAN) db:wipe

migrate:
	@$(ARTISAN) migrate

fresh:
	@$(ARTISAN) migrate:fresh

seed:
	@$(ARTISAN) db:seed

frontend-build:
	@$(ARTISAN) platform:frontend

tinker:
	@$(ARTISAN) tinker

reset-db: truncate migrate seed

node-install:
	@$(NODE) install

node-watch-poll:
	@$(NODE) run watch-poll

node-watch:
	@$(NODE) run watch

node-dev:
	@$(NODE) run dev

node-prod:
	@$(NODE) run prod

composer-install:
	@$(APP) composer install

phpunit:
	@$(APP) vendor/bin/phpunit

fix_permissions:
	@$(APP_SU) chown www-data:www-data -R .
	@$(APP) find . -type d -exec chmod 755 {} \;
	@$(APP) find . -type f -exec chmod 644 {} \;
	@$(APP) find ./vendor/bin -type f -exec chmod 755 {} \;



