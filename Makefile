DOCKER_COMPOSE := docker compose

MYSQL := $(DOCKER_COMPOSE) exec -it mysql bash
PHP := $(DOCKER_COMPOSE) exec -it php bash

PHP_EXEC := $(PHP) -c

define artisan_exec
	$(PHP_EXEC) "php artisan $1"
endef

up:
	$(DOCKER_COMPOSE) up -d
down:
	$(DOCKER_COMPOSE) down
build:
	$(DOCKER_COMPOSE) build
up_build:
	$(DOCKER_COMPOSE) up -d --build
logs:
	$(DOCKER_COMPOSE) logs -f
ps:
	$(DOCKER_COMPOSE) ps

cldocker:
	docker stop $$(docker ps -q) || true; \
	docker rm $$(docker ps -aq) || true; \
	docker rmi $$(docker images -q) || true; \
	docker volume rm $$(docker volume ls -q) || true; \
	docker network rm $$(docker network ls -q) || true; \
	docker builder prune -a -f || true; \
	docker system prune -a --volumes -f || true;

install_deps:
	$(PHP_EXEC) "composer install"

clview:
	$(call artisan_exec,view:clear)
clroute:
	$(call artisan_exec,route:clear)
clconfig:
	$(call artisan_exec,config:clear)
clear:
	$(call artisan_exec,optimize:clear)
cache:
	$(call artisan_exec,config:cache)
test:
	$(call artisan_exec,test)

# OBS: " --force " foram mantidos apenas por conveniência. Ideal é não usar.
migrate:
	$(call artisan_exec,migrate --force)
seed:
	$(call artisan_exec,db:seed --force)
fresh:
	$(call artisan_exec,migrate:fresh --force)

mysql:
	$(MYSQL)
php:
	$(PHP)

stpclear: up install_deps fresh

setup: up install_deps fresh seed
