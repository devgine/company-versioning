##----------------------------------------------------------------------------------------------------------------------
##--------------------------------------------------- SAS - Makefile ---------------------------------------------------
##----------------------------------------------------------------------------------------------------------------------

DC=docker-compose
PHP_CONTAINER=php
NODE_CONTAINER=front
EXEC_PHP=$(DC) exec $(PHP_CONTAINER) php
EXEC_NODE=$(DC) exec $(NODE_CONTAINER)

.DEFAULT_GOAL := help
.PHONY: help
help : Makefile # Print commands help.
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## Docker commands
##----------------------------------------------------------------------------------------------------------------------
.PHONY: logs shell install-local

logs: ## View containers logs.
	$(DC) logs -f $(filter-out $@,$(MAKECMDGOALS))

shell: ## Run bash shell in php container.
	$(DC) exec php sh

# todo complete this job including the installation of node server
install-local: ## Install project on dev local project
	@echo "Ensure local project does not exist"
	$(MAKE) prune
	@echo "Build project container"
	$(MAKE) build
	$(MAKE) up
	@echo "Install project"
	@echo "Clean all non static directories"
	$(DC) exec php rm -rf symfony/vendor/* symfony/var/* symfony/*.cache
	sleep 15
	$(DC) exec -e COMPOSER_MEMORY_LIMIT=-1 php composer install
	$(EXEC_PHP) bin/console lexik:jwt:generate-keypair --skip-if-exists
	$(MAKE) migration
	$(EXEC_PHP) bin/console import:close_county
	$(EXEC_PHP) bin/console doctrine:fixtures:load --group default -n
	$(EXEC_PHP) bin/console fhir:referentiel:slot:speciality

##
## Symfony commands
##----------------------------------------------------------------------------------------------------------------------
.PHONY: composer console migration data-fixtures

composer: ## Run composer in php container.
	$(EXEC_PHP) composer $(filter-out $@,$(MAKECMDGOALS))

console: ## Run symfony console in php container.
	$(EXEC_PHP) php bin/console $(filter-out $@,$(MAKECMDGOALS))

migration: ## Execute doctrine migration.
	$(EXEC_PHP) bin/console doctrine:migration:migrate -n

data-fixtures: ## Execute doctrine fixtures.
	$(EXEC_PHP) bin/console doctrine:fixtures:load -n

##
## Tests
##----------------------------------------------------------------------------------------------------------------------
.PHONY: unit-tests unit-tests-coverage

unit-tests: ## Run unit tests.
	$(EXEC_PHP) vendor/bin/phpunit

# todo update generated URL
unit-tests-coverage: ## Run unit tests with code coverage generate.
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=public/coverage/html/unit --coverage-php=public/coverage/php/phpunit.cov
	@echo "See coverage result here : https://symfony.localhost/coverage/html/unit/index.html"

##
## Quality code
##----------------------------------------------------------------------------------------------------------------------
.PHONY: fix fix-dry-run pstan eslint

fix: ## Runs the CS fixer to fix the project coding style.
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix -vvv --config=.php-cs-fixer.dist.php --cache-file=.php-cs-fixer.cache $(filter-out $@,$(MAKECMDGOALS))

fix-dry-run: ## Runs the CS fixer to sniff the project coding style.
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix -vvv --config=.php-cs-fixer.dist.php --cache-file=.php-cs-fixer.cache --dry-run

phpstan: ## Run phpstan analyses.
	$(EXEC_PHP) bin/console cache:warmup
	$(EXEC_PHP) ./vendor/bin/phpstan analyse -c phpstan.neon

eslint: ## Run the ESLinter to sniff typescript code.
	$(EXEC_NODE) yarn eslint --cache --cache-location ./.eslintcache --color --quiet .

##
## Tests and Quality check
##----------------------------------------------------------------------------------------------------------------------
.PHONY: ci
ci: ## Execute all tests and linters in a single command.
	$(MAKE) unit-tests
	$(MAKE) fix-dry
	$(MAKE) phpstan
	$(MAKE) eslint

##
## Security checker
##----------------------------------------------------------------------------------------------------------------------
.PHONY: security-checker audit

security-checker: ## Identify security flaws in PHP dependencies.
	./local-php-security-checker --path=./symfony/composer.lock

audit: ## Identify vulnerabilities in node packages.
	$(EXEC_NODE) yarn audit

##
## Run SQL
##----------------------------------------------------------------------------------------------------------------------
.PHONY: sql

sql: ## Executes arbitrary SQL with default connection
	$(EXEC_PHP) php bin/console dbal:run-sql "$(filter-out $@,$(MAKECMDGOALS))"
