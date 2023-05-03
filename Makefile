##----------------------------------------------------------------------------------------------------------------------
##------------------------------------------------------ Makefile ------------------------------------------------------
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
.PHONY: logs shell-php shell-node install-local

logs: ## View containers logs.
	$(DC) logs -f $(filter-out $@,$(MAKECMDGOALS))

shell-php: ## Run bash shell in php container.
	$(DC) exec $(PHP_CONTAINER) sh

shell-node: ## Run bash shell in node container.
	$(DC) exec $(NODE_CONTAINER) sh

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
	$(EXEC_PHP) exec php composer install
	$(MAKE) migration
	$(EXEC_PHP) bin/console php bin/console app:legal-statuses:import
	$(EXEC_PHP) bin/console doctrine:fixtures:load -n

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
.PHONY: php-unit-tests php-unit-tests-coverage node-unit-tests node-unit-tests-coverage

php-unit-tests: ## Run php unit tests.
	$(EXEC_PHP) vendor/bin/phpunit

# todo update generated URL
php-unit-tests-coverage: ## Run php unit tests with code coverage generate.
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=public/coverage/html/unit --coverage-php=public/coverage/php/phpunit.cov
	@echo "See coverage result here : https://symfony.localhost/coverage/html/unit/index.html"

node-unit-tests: ## Run typescript unit tests.
	$(EXEC_NODE) yarn test # todo: replace with test:clover

node-unit-tests-coverage: ## Run typescript unit tests with code coverage generate.
	$(EXEC_NODE) yarn test:coverage
	@echo "See coverage result here : http://localhost:3000/coverage/lcov-report/index.html"

##
## Code quality
##----------------------------------------------------------------------------------------------------------------------
.PHONY: fix fix-dry-run phpstan lint prettier prettier-check

fix: ## Runs the CS fixer to fix the project coding style.
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix -vvv --config=.php-cs-fixer.dist.php --cache-file=.php-cs-fixer.cache $(filter-out $@,$(MAKECMDGOALS))

fix-dry-run: ## Runs the CS fixer to sniff the project coding style (without fix).
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix -vvv --config=.php-cs-fixer.dist.php --cache-file=.php-cs-fixer.cache --dry-run

phpstan: ## Run phpstan analyses.
	$(EXEC_PHP) ./vendor/bin/phpstan analyse -c phpstan.neon

lint: ## Run the ESLinter to analyse typescript code.
	$(EXEC_NODE) yarn lint

prettier: ## Run the prettier to fix typescript code quality.
	$(EXEC_NODE) yarn prettier

prettier-check: ## Run the prettier to check typescript code quality.
	$(EXEC_NODE) yarn prettier:check

##
## Continuous integration
##----------------------------------------------------------------------------------------------------------------------
.PHONY: ci ci-php ci-node
ci-php: ## Execute tests and code quality for PHP container.
	$(MAKE) php-unit-tests
	$(MAKE) fix-dry-run
	$(MAKE) phpstan

ci-node: ## Execute tests and code quality for node container.
	$(MAKE) prettier
	$(MAKE) lint
	$(MAKE) node-unit-tests

ci: ## Execute all tests and linters in a single command.
	$(MAKE) ci-php
	$(MAKE) ci-node

##
## Security
##----------------------------------------------------------------------------------------------------------------------
.PHONY: security-php security-node security

security-php: ## Identify vulnerabilities in PHP dependencies.
	./local-php-security-checker --path=./symfony/composer.lock

security-node: ## Identify vulnerabilities in node packages.
	$(EXEC_NODE) yarn audit

security: ## Identify vulnerabilities in PHP and node packages.
	$(MAKE) security-php
	$(MAKE) security-node

##
##* Run SQL
##----------------------------------------------------------------------------------------------------------------------
.PHONY: sql

sql: ## Executes arbitrary SQL with default connection
	$(EXEC_PHP) php bin/console dbal:run-sql "$(filter-out $@,$(MAKECMDGOALS))"
