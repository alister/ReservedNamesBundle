all: lint test tidy

lint:
	ant php-lint-ci

test: security full-ant security

full-ant:
	ant manual

phpcs:
	ant phpcs

# Run composer, with HHVM
hhvm-composer-update:
	hhvm -v ResourceLimit.SocketDefaultTimeout=30 -v Http.SlowQueryThreshold=30000 /usr/local/bin/composer update

composer-update:
	composer update

tidy:
	find . -type d -name 'vendor' -prune -o  \( -perm /ugo=x -iname '*.md' -o -iname '*php' -o -iname '*.yml' -o -iname '*.xml' -o -iname 'Makefile' \) -print | xargs chmod -x

security:
	vendor/bin/security-checker   security:check
