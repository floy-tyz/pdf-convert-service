-include .env

DOCKER_COMPOSE := docker compose

RUN=$(DOCKER_COMPOSE) run --rm app
EXEC?=docker exec -it $(CONTAINER_NAME)-app
COMPOSER=$(EXEC) composer

POSTGRES_CONF_PHPUNIT=export APP_ENV=postgres

.PHONY: up down stop prune ps shell logs mutagen

default: up

help:                                        ## Список доступных команд
	@grep -hE '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-40s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

start: build up db perm ## Установка и запуск проекта

start-quick: build-quick up db perm

restart: down up                             ## Перезапуск контейнера

down:
	$(DOCKER_COMPOSE) -p $(CONTAINER_NAME) down

down-volume:
	$(DOCKER_COMPOSE) down -v

stop:                                        ## Остановить
	$(DOCKER_COMPOSE) stop

up:
	$(DOCKER_COMPOSE) -f docker-compose.yaml -p $(CONTAINER_NAME) up --remove-orphans

up-force:
	$(DOCKER_COMPOSE) -f docker-compose.yaml -p $(CONTAINER_NAME) up --remove-orphans --force-recreate

remove:	                                     ## Удалить контейнеры докеров
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -v --force
	rm -rf ./vendor ./var/*

reset: remove start                          ## Сбрость, пересобрать

build:
	$(DOCKER_COMPOSE) pull --ignore-pull-failures
	$(DOCKER_COMPOSE) -p $(CONTAINER_NAME) build --force-rm --build-arg APP_ENV=dev --pull

build-quick:
	$(DOCKER_COMPOSE) build --force-rm --build-arg APP_ENV=dev

mutagen:
	mutagen-compose up

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	$(DOCKER_COMPOSE) down -v $(filter-out $@,$(MAKECMDGOALS))

logs:
	$(DOCKER_COMPOSE) logs -f $(filter-out $@,$(MAKECMDGOALS))

wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen('postgres',5432)){echo \"db already\n\"; break;}echo \"Waiting for db\n\";sleep(1);}"

db: vendor wait-for-db
	$(EXEC) console doctrine:migrations:migrate -n

vendor:
	$(COMPOSER) install -n

composer:                                    ## Выполнить composer в контейнере app
	echo $(c)
	@${COMPOSER} $(c)

app-shell:                                   ## Открыть shell приложения (app)
	@$(EXEC) bash

clear: perm                                  ## Очистить кеш
	-$(EXEC) rm -rf var/cache/*
	-$(EXEC) rm -rf var/sessions/*
	-$(EXEC) rm -rf var/log/*

perm:
	$(EXEC) chown -R 1000:1000 var