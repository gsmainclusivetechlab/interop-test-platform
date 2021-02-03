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

```bash
# build docker images
$ yarn build

# install app dependencies inside container (synced to host with docker volumes)
$ yarn deps

# Set up the database using Laravel's migration tool
$ yarn seed

# launch containers
$ yarn dev
```

### Updates

After making changes to the code, you can deploy your changes by running almost
the same steps as the first run. The only difference is the migration service to
run, which should not seed the database with initial contents:

```bash
# build docker images
$ yarn build

# install app dependencies inside container (synced to host with docker volumes)
$ yarn deps

# Update up the database using Laravel's migration tool
$ yarn migrate

# launch containers
$ yarn dev
```

### mTLS

Client CA certificates renews every one hour. To renew CA certificates manually
please run:

```bash
$ yarn mtls:renew
```

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed above.

```
$ docker-compose exec {service} sh
```

### Permissions Issues

The default development image uses volumes to synchronise app code and
dependencies with the host machine. This may result in file ownership problems,
since the files must be read/writable by users both inside the container and the
host machine.

The user inside the container runs in a user group with id 1024. To ensure that
there are no permissions issues, you should ensure that your host machine also
has a user group with id 1024.

Mac:

```sh
dscl . -create /Groups/interop-devs
dscl . -create /Groups/interop-devs name interop-devs
dscl . -create /Groups/interop-devs passwd "*"
dscl . -create /Groups/interop-devs gid 1024
dscl . -create /Groups/interop-devs GroupMembership ${your-username}
```

Linux:

```sh
groupadd -g 1024 interop-devs
adduser ${your-username} interop-devs
```

Once the group is created, you may need to ensure that the source code is owned
and editable by this group:

```sh
chown -R :1024 src/
chmod -R 775 src/
chmod -R g+s src/
```

### Production builds

Using volumes for code synchronisation is convenient in development, but no good
for repeatable builds required in production. To build a production image, which
contains the app code and dependencies embedded inside the container image, run:

```bash
$ yarn build:prod
```

### Running Tests

Before running the tests, make sure that no containers are already running using
`yarn down`. Once everything has stopped, you can run tests using `yarn test`.
After the tests run, this will copy the test result files into the `./results`
directory. Coverage reports will also be generated inside the `./results`
directory in both HTML and clover formats.

As well as the PHP unit tests, it is possible to run browser tests through Dusk
and Selenium with `yarn dusk`. When running this, an additional docker container
will be launched to run an externally-controllable Selenium Chrome browser.

### Site access

You can access the app at http://localhost:8080 and the mail catcher at
http://localhost:8086.

Default superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
