# GSMA Interoperability Test Platform

[![Codacy grade](https://img.shields.io/codacy/grade/8ff2b7590e13431dad7032a973d908fd?logo=codacy)](https://www.codacy.com/gh/gsmainclusivetechlab/interop-test-platform?utm_source=github.com&utm_medium=referral&utm_content=gsmainclusivetechlab/interop-test-platform&utm_campaign=Badge_Grade)

[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/master?label=Master&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=master)
[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/develop?label=Develop&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=develop)

## Project Architecture

The API simulator is built using microservices, coordinated using
`docker-compose`. Our services are:

-   `app`: Main service containing application code and an nginx server. Uses a
    [custom image](./src/Dockerfile) based on Alpine Linux. PHP dependencies are
    installed with composer and artisan.
-   `queue`: Provides an environment to run Laravel queues. Uses the same image
    as `app`.
-   `migrate`: A short-lived service which simply runs database migrations
    before exiting. Uses the same image as `app`, which contains
    [wait](https://github.com/ufoscout/docker-compose-wait), which allows the
    service to wait for the `mysqldb` container to be running before attempting
    the migrations.
-   `mysqldb`: Provides a database for the app. Uses a lightly-customised
    off-the-shelf [mysql image](./mysqldb/Dockerfile.mysqldb). The customisation
    is just to inject our [mysql config](./mysqldb/my.cnf) into the container.
-   `redis`: Provides a durable message queue to communicate between parts of
    our app. Uses an off-the-shelf redis image.
-   `mailhog`: Provides an SMTP server to send emails. Uses an off-the-shelf
    mailhog image.
-   `phpmyadmin`: Optionally provides a DB administration interface, useful for
    local debugging.

## Project setup

1. Clone this repository
2. Navigate to the project directory
3. Copy the example environment file, and make any adjustments to reflect your
   own environment:
    - [.env.example](./.env.example) should be copied to `.env`
4. Install development dependencies with `yarn install`

### First run

1. Build new docker images:
    ```
    $ yarn build
    ```
2. Set up the database using Laravel's migration tool
    ```
    $ yarn seed
    ```
3. Launch containers using the new images:
    ```
    $ yarn prod
    ```

### Updates

After making changes to the code, you can deploy your changes by running almost
the same steps as the first run. The only difference is the migration service to
run, which should not seed the database with initial contents:

```
# rebuild all images to include the new code
$ yarn build

# run the default migration script, which does not seed
$ yarn migrate

# stop and destroy existing containers, then recreate using the new images
$ yarn prod
```

## Local development

When running in different environments, we may want our services to operate in a
slightly different way. In particular, it is annoying to continually rebuild
images for every small change. Additional configuration files have been set up
to cover some such cases:

-   [`compose/ops.yml`](./compose/ops.yml): Defines short-lived services
    `migrate` and `seed` to update or setup the database respectively, and
    `test` to run unit tests.
-   [`compose/volumes.yml`](./compose/volumes.yml): Set up shared volumes
    between your local files and the files inside the running containers, which
    allows your local changes to immediately be reflected in the running
    containers without rebuilding.
-   [`compose/network.yml`](./compose/network.yml): Set up a shared external
    docker network. This is useful when you also have other test components
    (e.g. simulators) running locally, as it will allow all services to
    communicate across the same docker network.
-   [`compose/expose-web.yml`](./compose/expose-web.yml): Allow the test
    platform to be accessed on your local machine under port 8084, along with
    the mailhog test SMTP interfact on port 8086 (or whatever is configured as
    `HOST_WEB_PORT` and `HOST_MAILHOG_PORT` in [.env](./.env.example)).
-   [`compose/mailhog.yml`](./compose/mailhog.yml): Set up a mailhog service,
    allowing emails to be tested without a real SMTP server.
-   [`compose/production.yml`](./compose/production.yml): Configure all services
    to restart automatically when they crash (or the server restarts).
    Additionally persists redis and mysql data to the host file system for
    backup purposes.
-   [`compose/phpmyadmin.yml`](./compose/phpmyadmin.yml): Add an additional
    service running [PHPMyAdmin](https://www.phpmyadmin.net/) for inspecting the
    test platform database. To use these configurations, select the config files
    when running any `docker-compose` command:

```
$ docker-compose -f ./docker-compose.yml \
                 -f ./compose/expose-web.yml
                 -f ./compose/network.yml
                 -f ./compose/volumes.yml
                 up -d
```

These configuration files can be used in any combination, however two preset
combinations have been added to the top-level package.json file to allow
shortcuts for common use-cases:

-   `yarn dev`: includes `expose-web`, `mailhog`, `network`, `phpmyadmin` and
    `volumes`.
-   `yarn prod`: includes `expose-web` and `production.yml`

### Modifying client assets - TODO: this is going to change

Client assets (javascript, scss, images, etc) are compiled by webpack using
Laravel Mix. Each time the source for these assets is modified, they will need
to be recompiled. Webpack can optionally watch the files for changes, and
automatically recompile them as soon as they have been modified. To enable this,
run `yarn watch` instead of `yarn dev`. If you have started a running container
using the `./compose/volumes.yml` configuration, changes should be visible
inside the browser immediately.

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed above.

```
$ docker-compose exec {service} sh
```

### Running Tests

The current test suite only targets PHP code, which means that the test runner
must run inside the php containers. Before running the tests, make sure that no
containers are already running using `yarn down`. Once everything has stopped,
you can run tests using `yarn test`. This will generate a test result file
inside the `./results` directory. To run the tests and calculate test coverage
(can be slower), run `yarn test-coverage` instead. Coverage reports will be
generated inside the `./results` directory in both HTML and clover formats.

### Site access

If you have used the `./compose/expose-web.yml` config, you can access the app
at http://localhost:8080 and the mail catcher at http://localhost:8086.

Default superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
