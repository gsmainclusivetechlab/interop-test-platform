# Interoperability Test Platform

[![Codacy grade](https://img.shields.io/codacy/grade/8ff2b7590e13431dad7032a973d908fd?logo=codacy)](https://www.codacy.com/gh/gsmainclusivetechlab/interop-test-platform?utm_source=github.com&utm_medium=referral&utm_content=gsmainclusivetechlab/interop-test-platform&utm_campaign=Badge_Grade)

[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/master?label=Master&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=master)
[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/develop?label=Develop&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=develop)

## Project Architecture

The test platform is built using micro-services, which means that we have a
bunch of "_containers_" (basically mini-computers) each doing one thing in
particular. The thing that each container does is defined by the container
_image_. An image is like a snapshot of machine, which describes what the
container should look like, and what it should do when it starts.

Our services are:

- `mailhog`: Provides an SMTP server to send emails. Uses an off-the-shelf mailhog image.
- `redis`: Provides a durable message queue to communicate between parts of
  our app. Uses an off-the-shelf redis image.
- `mysqldb`: Provides a database for the app. Uses a lightly-customised
  off-the-shelf [mysql image](./Dockerfile.mysqldb). The customisation is just to
  inject our [mysql config](./build/my.cnf) into the container.
- `web`: Provides an nginx web server. Uses a lightly-customised [nginx
  image](./Dockerfile.web). The customisation is to add our application code
  to the container, and similarly adds [server
  config](./build/nginx-server.conf), [SSL config](./build/ssl).
- `app`: Provides a PHP interpreter to run our application code. Uses a
  [custom image](http://github.com/gsmainclusivetechlab/interop-php-fpm),
  further customised to add configuration files and the application code. In
  addition, PHP dependencies are installed with composer and artisan.
- `queue`: Provides an environment to run Laravel queues. Uses the same image as `app`.
- `migrate`: A short-lived service which simply runs database migrations
  before exiting. Uses the same image as `app`, which contains
  [wait](https://github.com/ufoscout/docker-compose-wait), which allows the
  service to wait for the `mysqldb` container to be running before attempting
  the migrations.
- `nodejs`: A short-lived service which compiles client-side assets (e.g.
  SCSS files) for subsequent inclusion in the `web` image above. Uses a
  [custom image](http://github.com/gsmainclusivetechlab/interop-nodejs), further
  customised to add application code and install node dependencies. Can also be run
  as a long-lived service, in which case it will monitor changes to the assets and
  compile them immediately.

These services are coordinated with docker-compose. There are two
docker-compose files in this repo: one for
[development](./docker-compose.yml) and one for
[production](./docker-compose.prod.yml). The primary difference is that the
development config includes several docker volumes which synchronise certain
files in the running containers with files on the host machine. This makes it
easy to make changes to files locally which will immediately be reflected in
the containers (no need to rebuild the image). Additionally, the `nodejs`
does not run in production, since the assets will not change to need
recompilation. Instead, the assets can be compiled once before the production
images are built.

## Running the Test Platform

1. Clone this repository
2. Navigate to the project directory
3. Copy the example environment files, and make any adjustments to reflect your own environment:
   - .env.example should be copied to .env
   - src/.env.example should be copied to src/.env
4. Check .env files for correct configurations.

### In Development

5. Prepare the database with migrations:
   `docker-compose run migrate`
6. Run application:
   `docker-compose up web`

### In Production

5. Prepare the database with migrations:
   `docker-compose -f ./docker-compose.prod.yml run migrate`
6. Compile client-side assets
   `docker-compose -f ./docker-compose.prod.yml run nodejs`
7. Run application:
   `docker-compose -f ./docker-compose.prod.yml up web`

### Site access:

Access your site via URL: <http://localhost:8084>

Mail catcher: <http://localhost:8086>

Superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy

### Running tests

To run PHP tests, first ensure that no containers are already running with `docker-compose stop`. Then run:

```
docker-compose run app php artisan dusk --testdox --log-junit tests/results/results.xml
docker-compose run app phpdbg -qrr vendor/bin/phpunit --coverage-html tests/results/coverage-report-html --coverage-clover tests/results/coverage-report-clover/clover.xml
```

TODO: consider using a Makefile or similar to add shortcuts for the commands above

### Container access:

To run commands inside a running service container, run:

```
docker-compose run SERVICE_NAME_GOES_HERE bash
```

### Stopping the containers

To stop all running containers, simply run `docker-compose down`.

<!--
## TODO:

 - Review whether `make init` is necessary. .env is probably necessary, src/.env seems
   suspicious, docker-compose is definitely not, and nginx-server also seems
   suspicious. Could probably manually copy those files rather than magick with make.
- There's a permissions issue with the shared mysql volume which means that `docker-compose` needs to be run with sudo after the first instance.
- remove unneeded makefiles (possibly replace with just shortcuts for the above steps)
- hook up to CircleCI for CI
- try to manually deploy
- hook up to CircleCI for CD

 -->
