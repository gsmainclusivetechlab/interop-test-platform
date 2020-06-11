# GSMA Interoperability Test Platform

[![Codacy grade](https://img.shields.io/codacy/grade/8ff2b7590e13431dad7032a973d908fd?logo=codacy)](https://www.codacy.com/gh/gsmainclusivetechlab/interop-test-platform?utm_source=github.com&utm_medium=referral&utm_content=gsmainclusivetechlab/interop-test-platform&utm_campaign=Badge_Grade)

[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/master?label=Master&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=master)
[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/develop?label=Develop&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=develop)

## Project Architecture

The API simulator is built using microservices, coordinated using
`docker-compose`. Our services are:

- `mailhog`: Provides an SMTP server to send emails. Uses an off-the-shelf
  mailhog image.
- `redis`: Provides a durable message queue to communicate between parts of
  our app. Uses an off-the-shelf redis image.
- `mysqldb`: Provides a database for the app. Uses a lightly-customised
  off-the-shelf [mysql image](./Dockerfile.mysqldb). The customisation is just
  to inject our [mysql config](./build/my.cnf) into the container.
- `web`: Provides an nginx web server. Uses a lightly-customised
  [nginx image](./Dockerfile.web). The customisation is to add our application
  code to the container, and similarly adds
  [server config](./build/nginx-server.conf), [SSL config](./build/ssl).
- `app`: Provides a PHP interpreter to run our application code. Uses a
  [custom image](http://github.com/gsmainclusivetechlab/interop-php-fpm),
  further customised to add configuration files and the application code. In
  addition, PHP dependencies are installed with composer and artisan.
- `queue`: Provides an environment to run Laravel queues. Uses the same image
  as `app`.
- `migrate`: A short-lived service which simply runs database migrations
  before exiting. Uses the same image as `app`, which contains
  [wait](https://github.com/ufoscout/docker-compose-wait), which allows the
  service to wait for the `mysqldb` container to be running before attempting
  the migrations.

## Project setup

1. Clone this repository
2. Navigate to the project directory
3. Copy the example environment files, and make any adjustments to reflect your
   own environment:
   - [.env.example](./.env.example) should be copied to `.env`
   - [src/.env.example](./src/.env.example) should be copied to `src/.env`
4. Install development dependencies with `npm install`

### First run

1. Build client-side assets with
   ```
   $ npm run dev
   ```
2. Build new docker images:
   ```
   $ docker-compose build
   ```
3. Set up the database using Laravel's migration tool
   ```
   $ docker-compose -f ./docker-compose.yml -f ./development/migrate.yml run seed
   ```
4. Launch containers using the new images:
   ```
   $ docker-compose up -d
   ```

### Updates

After making changes to the code, you can deploy your changes by running almost
the same steps as the first run. The only difference is the migration service to
run, which should not seed the database with initial contents:

```
# Rebuild client-side assets
npm run dev

# rebuild all images to include the new code
$ docker-compose build

# run the default migration script, which does not seed
$ docker-compose -f ./docker-compose.yml -f ./development/migrate.yml run migrate

# stop and destroy existing containers, then recreate using the new images
$ docker-compose up -d
```

## Local development

When running locally, we may want our services to operate in a slightly
different way. In particular, it is annoying to continually rebuild images
for every small change. Additional configuration files have been set up to
cover some such cases:

- [`development/migrate.yml`](./development/migrate.yml): Defines two
  short-lived services `migrate` and `seed` to update or setup the database
  respectively.
- [`development/volumes.yml`](./development/volumes.yml): Set up shared
  volumes between your local files and the files inside the running
  containers, which allows your local changes to immediately be reflected in
  the running containers without rebuilding.
- [`development/network.yml`](./development/network.yml): Set up a shared
  external docker network. This is useful when you also have other test
  components (e.g. simulators) running locally, as it will allow all services
  to communicate across the same docker network.
- [`development/expose-web.yml`](./development/expose-web.yml): Allow the test
  platform to be accessed on your local machine under port 8084, along with
  the mailhog test SMTP interfact on port 8086 (or whatever is configured as
  `HOST_WEB_PORT` and `HOST_MAILHOG_PORT` in [.env](./.env.example)).

To use these configurations, select the config files when running any
`docker-compose` command:

```
$ docker-compose -f ./docker-compose.yml \
                 -f ./development/expose-web.yml
                 -f ./development/network.yml
                 -f ./development/volumes.yml
                 up -d
```

### Modifying client assets

Client assets (javascript, scss, images, etc) are compiled by webpack using
Laravel Mix. Each time the source for these assets is modified, they will need
to be recompiled. Webpack can optionally watch the files for changes, and
automatically recompile them as soon as they have been modified. To enable this,
run `npm run watch` instead of `npm run dev`. If you have started a running
container using the `./development/volumes.yml` configuration, changes should be
visible inside the browser immediately.

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed above.

```
$ docker-compose exec {service} bash
```

### Running Tests

The current test suite only targets PHP code, which means that the test runner
must run inside the php containers. Before running the tests, make sure that no
containers are already running using `docker-compose down`. Once everything has
stopped, you can run tests using `npm test`. This will generate a test result
file inside the `./runtime/results` directory. To run the tests and calculate
test coverage (can be slower), run `npm run test-coverage` instead. Coverage
reports will be generated inside the `./runtime/results` directory in both HTML
and clover formats.

### Site access

If you have used the `./development/expose-web.yml` config, you can access
the app at http://localhost:8080 and the mail catcher at
http://localhost:8086.

Default superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
