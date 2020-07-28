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
2. Set up the database using Laravel's migration tool
    ```bash
    $ yarn seed
    ```
3. Launch containers using the new images:
    ```bash
    $ yarn prod
    ```

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
-   `yarn prod`: includes `expose-web` and `production.yml`

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed above.

```
$ docker-compose exec {service} sh
```

### Node dependencies

Mounting a single file (such as package.json) into a container using
docker-compose volumes makes it impossible to update that file from within the
container. To work around this, these files are placed inside the `.npm-package`
directory, which can be mounted without these problems. It's possible to install
new dependencies like so:

```bash
$ docker-compose -f docker-compose.yml \
    -f compose/webpack.yml \
    exec webpack npm install my-package --save-dev

# OR

$ docker-compose -f docker-compose.yml -f compose/webpack.yml exec webpack sh
$ npm install my-package --save-dev

# OR

# requires node on host machine, and creates a copy of node_modules on the host
$ npm install my-package --save-dev
```

Use `run` instead of `exec` if the containers are not already running.

By default, `node_modules` is not present on the host machine, since node need
not be installed there. If you would like to have the dependencies present on
the host (e.g. for IDE compatibility), you can simply run `npm install` on the
host machine. This will create a `node_modules` directory on the host, but note
that this will not be copied into the running container (instead it will be
independently re-created as part of the image build process using `package.json`
and `package-lock.json`).

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
