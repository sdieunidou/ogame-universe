FIG=docker-compose
RUN=$(FIG) exec -T
EXEC=$(FIG) exec -T
CONSOLE=php bin/console

.PHONY: help start stop build up reset config db-diff db-migrate vendor reload test assets

help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  reload   clear cache, reload database schema and load fixtures (only for dev environment)"

##
## Docker
##---------------------------------------------------------------------------

start:          ## Install and start the project
start: build up

stop:           ## Remove docker containers
	$(FIG) kill
	$(FIG) rm -v --force

reset:          ## Reset the whole project
reset: stop start

build:
	$(FIG) build

up:
	$(FIG) up -d

vendor:           ## Vendors
	$(RUN) php-cli composer install

config:        ## Init files required
	cp .env .env.local
	cp docker-compose.override.yml.dist docker-compose.override.yml

install:          ## Install the whole project
install: config start vendor reload assets

clear:          ## Remove all the cache, the logs, the sessions and the built assets
	$(EXEC) php-cli rm -rf var/cache/*
	$(EXEC) php-cli rm -rf var/log/*

##
## DB
##---------------------------------------------------------------------------

db-diff:      ## Generation doctrine diff
	$(RUN) php-cli $(CONSOLE) doctrine:migrations:diff

db-migrate:   ## Launch doctrine migrations
	$(RUN) php-cli $(CONSOLE) doctrine:migrations:migrate -n

## Others
reload: clear

assets:   ## Launch doctrine migrations
	$(RUN) php-cli $(CONSOLE) assets:install --symlink
	#$(RUN) php-cli yarn run encore dev
	$(RUN) php-cli yarn run encore production
