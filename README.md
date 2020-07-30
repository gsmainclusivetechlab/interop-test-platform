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
-   `mysqldb`: Provides a database for the app. Uses a lightly-customised
    off-the-shelf [mysql image](./mysqldb/Dockerfile.mysqldb). The customisation
    is just to inject our [mysql config](./mysqldb/my.cnf) into the container.
-   `redis`: Provides a durable message queue to communicate between parts of
    our app. Uses an off-the-shelf redis image.
-   `mailhog`: Optionally provides an SMTP server to send emails. Uses an
    off-the-shelf mailhog image.
-   `phpmyadmin`: Optionally provides a DB administration interface, useful for
    local debugging.
-   `webpack`: Optionally provides a file watcher which recompiles front-end
    assets as soon as they change. Useful for local development.

## Project setup

1. Clone this repository
2. Navigate to the project directory
3. Copy the example environment file, and make any adjustments to reflect your
   own environment:
    - [.example.env](./.example.env) should be copied to `.env`
    - [service.example.env](./service.example.env) should be copied to
      `service.env`
4. Install development dependencies with `yarn install`

### First run

1. Build new docker images:
    ```bash
    $ yarn build
    ```
2. Copy dependencies to your local dev environment[\*](#1)
    ```bash
    $ yarn prepare:dev
    ```
3. Set up the database using Laravel's migration tool
    ```bash
    $ yarn seed
    ```
4. Launch containers using the new images:
    ```bash
    $ yarn prod
    ```

<span id="1">\*</span>This is required to populate dependencies on the host
(useful for IDEs) and when using volumes to synchronise source code (otherwise
the empty host dependency directories will overwrite those inside the
container).

### Updates

After making changes to the code, you can deploy your changes by running almost
the same steps as the first run. The only difference is the migration service to
run, which should not seed the database with initial contents:

```bash
# rebuild all images to include the new code
$ yarn build

# run the default migration script, which updates but does not seed the database
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
    `migrate`, `seed` and `backup` to update, setup or backup the database
    respectively, and `test` to run unit tests.
-   [`compose/volumes.yml`](./compose/volumes.yml): Set up shared volumes
    between your local files and the files inside the running containers, which
    allows your local changes to immediately be reflected in the running
    containers without rebuilding.
-   [`compose/ops-volumes.yml`](./compose/ops-volumes.yml): Does the same things
    as `compose/volumes.yml` but targetting the services defined in `ops`.
    Useful for running new migrations or tests.
-   [`compose/network.yml`](./compose/network.yml): Set up a shared external
    docker network. This is useful when you also have other test components
    (e.g. simulators) running locally, as it will allow all services to
    communicate across the same docker network.
-   [`compose/expose-web.yml`](./compose/expose-web.yml): Allow the test
    platform to be accessed on your local machine under port 8084 (or whatever
    is configured as `HOST_WEB_PORT` in [.env](./.example.env)).
-   [`compose/production.yml`](./compose/production.yml): Configure all services
    to restart automatically when they crash (or the server restarts).
-   [`compose/mailhog.yml`](./compose/mailhog.yml): Set up a mailhog service,
    allowing emails to be tested without a real SMTP server.
-   [`compose/phpmyadmin.yml`](./compose/phpmyadmin.yml): Add an additional
    service running [PHPMyAdmin](https://www.phpmyadmin.net/) for inspecting the
    test platform database. To use these configurations, select the config files
    when running any `docker-compose` command:
-   [`compose/webpack.yml`](./compose/webpack.yml): Add an additional service
    running Webpack, which will watch for changes to the front-end javascript
    assets (e.g. Vue templates) and recompile them automatically. This must be
    used in conjunction with the `compose/volumes.yml` config, otherwise the
    changes will not be reflected inside running container.

To use these configurations, select the config files when running any
`docker-compose` command:

```
$ docker-compose -f ./docker-compose.yml \
                 -f ./compose/expose-web.yml \
                 -f ./compose/network.yml \
                 -f ./compose/volumes.yml \
                 up -d
```

These configuration files can be used in any combination, however several preset
combinations have been added to the top-level package.json file to allow
shortcuts for common use-cases:

-   `yarn dev`: includes `expose-web`, `mailhog`, `network`, `phpmyadmin`,
    `volumes` and `webpack`.
-   `yarn dev:run`: includes the same services as `yarn dev` but uses
    `docker-compose run` for one-time operations instead of `docker-compose up`.
-   `yarn prod`: includes `expose-web` and `production.yml`

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed above.

```
$ docker-compose exec {service} sh
```

### Installing dependencies

It's possible to install new dependencies like so:

```bash
# Composer
$ yarn dev:run app composer require {package}
# NPM
$ yarn dev:run webpack npm install {package} --save-dev
```

Remember to run `yarn dev:prepare` to copy dependencies from the containers onto
the host. If you don't do this, mounting any shared volumes will overwrite the
dependency directory within the running containers with the empty one from the
host. Adding dependencies with `dev:run` as above will actually add the
dependency to the host via a mounted volume. To permanently add the dependency
to the running container so that it is present in production mode, the image
will need to be rebuilt with `yarn build`.

It's also important to set `HOST_UID` in the `.env` file to correspond to your
user ID on the host machine (run `id -u` to get this). This allows the
containers to write to files on the volume which are owned by your user (e.g. to
update package.json). This needs to be set _before_ the app images are built, as
files are created inside the container assuming this same ID.

### Running Tests

The current test suite only targets PHP code, which means that the test runner
must run inside the php containers. Before running the tests, make sure that no
containers are already running using `yarn down`. Once everything has stopped,
you can run tests using `yarn test`. After the tests run, this will copy the
test result files into the `./results` directory. Coverage reports will also be
generated inside the `./results` directory in both HTML and clover formats.

### Site access

If you have used the `./compose/expose-web.yml` config, you can access the app
at http://localhost:8080 and the mail catcher at http://localhost:8086.

Default superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
