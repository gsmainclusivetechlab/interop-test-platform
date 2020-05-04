## Interoperability Test Platform

### Installation

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
5. Run containers with `make run`
6. Run installation `make install`  

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

Access your site via URL: http://localhost:8084

Mail catcher: http://localhost:8086

Superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy