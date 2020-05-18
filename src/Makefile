.PHONY: info install update composer-install ci composer-update composer-init cu composer-autoload ca chmod cache-clear

COMPOSER_OPTS =

MAYBE_SUDO = sudo
ifneq "$(NOSUDO)" ""
	MAYBE_SUDO =
endif

# INSTALL AFTER CLONE
install: chmod composer-install cache-clear
	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan dusk:chrome-driver

update: chmod composer-install cache-clear
	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan queue:restart
	php artisan dusk:chrome-driver

test:
	#php artisan dusk --testdox --log-junit tests/results/results.xml

test-coverage:
	#phpdbg -qrr vendor/bin/phpunit --coverage-html tests/results/coverage-report

# Composer helpers
ci: composer-install
composer-install: composer-init
	@echo 'Install composer...'
	$(COMPOSER_OPTS) composer install

cu: composer-update
composer-update: composer-init
	@echo 'Update composer...'
	$(COMPOSER_OPTS) composer update

ca: composer-autoload
composer-autoload: composer-init
	$(COMPOSER_OPTS) composer dump-autoload

composer-init:
	@echo ''

#######################
# Helpers
#######################

chmod:
	@echo 'Chmod storage and bootstrap cache...'
	${MAYBE_SUDO} chmod -R 777 storage bootstrap/cache

cache-clear:
	@echo 'Clearing cache...'
	php artisan cache:clear
	php artisan view:clear
	php artisan config:clear
	php artisan route:clear
