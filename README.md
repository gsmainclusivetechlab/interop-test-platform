Webhooks Proxy Server. Powered by Laravel 
===================================

BASE STRUCTURE
-------------------
```
  build/		contains docker container config
  runtime/
    |-- bash/		Bash history (composer cache, bash commands history)
    |-- mysql/		MySQL databases for docker container
  src/			Laravel application code (Project)
```

INSTALLATION
------------

Project can be setup with Docker.

(If you have a Mac machine - we recommend to run docker under Vagrant. See [Vagranfile and Instructions](https://bitbucket.org/snippets/justcoded/Aex4nL/))

1. Clone repository
2. Navigate to your project directory
3. Run `make init`, this command will copy important files from examples:
    - .env
    - docker-compose.yml
    - src/.env
    - build/nginx-server.conf
4. Check .env files for correct configurations.
5. Login to JC docker hub: `docker login hub.jcdev.net:24000`
6. Run a test run to build containers and init DB: `make test-run`. After containers up press "Ctrl+C" to exit.
7. Run containers with `make run`
7. Add to your `/etc/hosts` file: `127.0.0.1 httpproxy.test`
9. Run installation `make install`  

### Docker PHP Container

To get inside PHP container to run composer/php commands run this command:

`make php-bash`

Inside PHP container there is also GNU Make utility, run `make` without any parameters to get available commands list.

### Docker nodejs container

To build nodejs you can use make helpers:

`make npm-i`
`make npm-build`
`make npm-watch`

To open nodejs container permanently just run:

`make nodejs-bash`

### Site access:

Access your site via URL: http://httpproxy.test:8082

Mail catcher: http://httpproxy.test:8086

Admin login: admin@domain.com / 123456

You're ready to write your code!
------------