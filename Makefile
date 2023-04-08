#!/usr/bin/make
.PHONY: *

SHELL := /bin/bash

help: ## This help dialog.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2 | "sort -u"}' $(MAKEFILE_LIST)

DOCKER_COMPOSE := docker compose

build: ## (re)build the images and start the containers
	$(DOCKER_COMPOSE) build --no-cache

install: ## Install the containers in place
	$(DOCKER_COMPOSE) up -d

uninstall: ## Remove the containers
	$(DOCKER_COMPOSE) down

cli-backend: ## Enter our backend environment shell
	$(DOCKER_COMPOSE) run --rm app bash

lint:
	$(DOCKER_COMPOSE) run --rm app sh ./vendor/bin/pint

autoload:
	$(DOCKER_COMPOSE) run --rm composer dump-autoload -o

npm:
	$(DOCKER_COMPOSE) run --rm npm run dev
