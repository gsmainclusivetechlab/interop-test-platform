.PHONY: info install update composer-install ci composer-update composer-init cu composer-autoload ca chmod cache-clear

info:
	@echo "LenderKit Application Utility (PHP-FPM Container)"
	@echo " "
	@echo "Usage:"
	@echo "	make command"
	@echo " "
	@echo "Available commands:"
	@echo "	install 			Install project after first time download"
	@echo "	update 				Update project after code update"
	@echo "	composer-install|ci		Run composer install w/ env options"
	@echo "	composer-update|cu		Run composer update w/ env options"
	@echo "	composer-autoload|ca		Run composer dump autoload w/ env options"
	@echo "	chmod 				Chmod storage and bootstrap folders"
	@echo "	cache-clear 			Clear all cache"

COMPOSER_OPTS =

MAYBE_SUDO = sudo
ifneq "$(NOSUDO)" ""
	MAYBE_SUDO =
endif

# INSTALL AFTER CLONE
install: chmod composer-install
	php artisan key:generate
	php artisan migrate:fresh --seed

update: chmod composer-install cache-clear
	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan queue:restart

test:
	php artisan dusk --testdox

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
