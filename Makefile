COMPOSER = docker run --rm -it -v $$PWD:/app -w /app -u $$(id -u):$$(id -g) composer:1.6
PHP = docker run --rm -it -v $$PWD:/app -w /app -u $$(id -u):$$(id -g) php:7.2

composer.lock: composer.json
	$(COMPOSER_16) composer update --prefer-lowest
	touch composer.lock

vendor: composer.lock
	$(COMPOSER) composer install
	touch vendor

example: vendor
	$(PHP) php example/example.php

test: tests
	$(PHP) php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml