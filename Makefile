all: lint test

lint:
	ant php-lint-ci

test:
	ant

phpcs:
	vendor/bin/phpcs -a --standard=PSR2 --extensions=php src/ tests

# Run composer, with HHVM
hhvm-composer-update:
	hhvm -v ResourceLimit.SocketDefaultTimeout=30 -v Http.SlowQueryThreshold=30000 /usr/local/bin/composer update

composer-update:
	composer update

tidy:
	find . -type d -name 'vendor' -prune -o  \( -perm /ugo=x -iname '*.md' -o -iname '*php' \) -print | xargs chmod -x
