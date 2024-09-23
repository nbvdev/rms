# Executables (local)
DOCKER_COMPOSE = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec app

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMPOSE) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMPOSE)  up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMPOSE) down --remove-orphans
