# ============================================================
# Makefile - Docker shortcuts
# ============================================================

.PHONY: help up down build restart logs shell db redis clean

APP_NAME ?= app

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ── Lifecycle ────────────────────────────────────────────────
up: ## Start all services
	docker compose up -d

up-dev: ## Start all services including dev tools (mailpit)
	docker compose --profile dev up -d

down: ## Stop all services
	docker compose down

build: ## Build PHP image
	docker compose build --no-cache php-fpm

restart: ## Restart all services
	docker compose restart

# ── Logs ─────────────────────────────────────────────────────
logs: ## Tail all logs
	docker compose logs -f

logs-php: ## Tail PHP-FPM logs
	docker compose logs -f php-fpm

logs-nginx: ## Tail Nginx logs
	docker compose logs -f nginx

logs-mysql: ## Tail MySQL logs
	docker compose logs -f mysql

# ── Shell access ─────────────────────────────────────────────
shell: ## PHP container shell
	docker compose exec php-fpm sh

shell-nginx: ## Nginx container shell
	docker compose exec nginx sh

# ── Database ─────────────────────────────────────────────────
db: ## MySQL CLI
	docker compose exec mysql mysql -u $${DB_USERNAME} -p$${DB_PASSWORD} $${DB_DATABASE}

db-root: ## MySQL CLI as root
	docker compose exec mysql mysql -u root -p$${DB_ROOT_PASSWORD}

db-dump: ## Dump database
	docker compose exec mysql mysqldump -u root -p$${DB_ROOT_PASSWORD} $${DB_DATABASE} > backup_$$(date +%Y%m%d_%H%M%S).sql

# ── Redis ─────────────────────────────────────────────────────
redis-cli: ## Redis CLI
	docker compose exec redis redis-cli -a $${REDIS_PASSWORD}

# ── Composer ─────────────────────────────────────────────────
composer-install: ## Install composer dependencies
	docker compose exec php-fpm composer install

composer-update: ## Update composer dependencies
	docker compose exec php-fpm composer update

# ── Maintenance ───────────────────────────────────────────────
clean: ## Remove containers, volumes and images
	docker compose down -v --rmi local

ps: ## Show running containers
	docker compose ps