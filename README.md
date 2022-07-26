# GSMA Interoperability Test Platform

[![CI/CD](https://github.com/gsmainclusivetechlab/interop-test-platform/actions/workflows/build.yml/badge.svg)](https://github.com/gsmainclusivetechlab/interop-test-platform/actions/workflows/build.yml)
[![codecov](https://codecov.io/gh/gsmainclusivetechlab/interop-test-platform/branch/develop/graph/badge.svg?token=Q4OZZ7H7PZ)](https://codecov.io/gh/gsmainclusivetechlab/interop-test-platform)

## Project Architecture

The API simulator is built using microservices, coordinated using
`docker-compose`. Our services are:

-   `app`: Main service containing application code and an nginx server. Uses a
    [custom image](./Dockerfiles/) based on Alpine Linux. PHP dependencies are
    installed with composer and artisan.
-   `queue`: Provides an environment to run Laravel queues. Uses the same image
    as `app`.
-   `mysqldb`: Provides a database for the app. Uses a lightly-customised
    off-the-shelf [mysql image](./mysqldb/Dockerfile). The customisation is just
    to inject our [mysql config](./mysqldb/my.cnf) into the container.
-   `redis`: Provides a durable message queue to communicate between parts of
    our app. Uses an off-the-shelf redis image.
-   `mailhog`: Optionally provides an SMTP server to send emails. Uses an
    off-the-shelf mailhog image.
-   `phpmyadmin`: Optionally provides a DB administration interface, useful for
    local debugging.
-   `certbot`: Provides and handles certificates generation using Let's Encrypt.

## Project setup

### Requirements

-   `docker`
-   `docker-compose`
-   `yarn`
-   `npm`
-   `nodejs`

### Setup

1. Clone this repository

    ```bash
    git clone https://github.com/gsmainclusivetechlab/interop-test-platform.git
    ```

2. Navigate to the project directory

    ```bash
    cd interop-test-platform
    ```

3. Configure the environment files `.env` and `services.env`. Below is the
   default configuration for the local deployment:

    ```bash
    export PROJECT_DOMAIN=www.example.com
    export DB_USERNAME=gsma
    export DB_PASSWORD=gsma
    export DB_DATABASE=itp
    export APP_ENV=development
    export APP_DEBUG=true
    export APP_URL=http://localhost:8084
    export COMMIT_HASH=$(git rev-parse --short HEAD)
    export COMMIT_TAG=$(git tag --contains ${COMMIT_HASH})

    cp .example.env .env
    envsubst < service.example.env > service.env
    ```

### Build

```bash
# install development dependencies
yarn install

# build docker images
yarn build

# install app dependencies inside container (synced to host with docker volumes)
yarn deps

# set up the database using Laravel's migration tool
yarn seed

# generate Laravel's APP_KEY
yarn genkey
```

Edit the file `service.env` and add to `APP_KEY` enviroment variable the value
generated in the step `yarn genkey`. The `APP_KEY` value should have been
printed in your console and looks like:

```bash
base64:3YFGVnVdzHlnUyMZZWv8xm0Hzaxb7lI/poHX6nOJ9yU=
```

### launch containers

```bash
yarn dev
```

### Updates

After making changes to the code, you can deploy your changes by running almost
the same steps as the first run. The only difference is the migration service to
run, which should not seed the database with initial contents:

```bash
# build docker images
yarn build

# install app dependencies inside container (synced to host with docker volumes)
yarn deps

# Update up the database using Laravel's migration tool
yarn migrate

# launch containers
yarn dev
```

### mTLS

Client CA certificates renews every one hour. To renew CA certificates manually
please run:

```bash
yarn mtls:renew
```

### Inspecting Running Containers

Running containers should not be modified, since the changes will be lost each
time the container restarts. However, it can be useful to connect to a running
container in order to inspect the environment and debug. To do that, use the
following command, where `{service}` can be any of the services listed in the
section [Project Architecture](#project-architecture).

```bash
docker-compose run {service} sh
```

For example, the following command is used to access a shell inside the
container of the service `app`:

```bash
docker-compose run app sh
```

### Permissions Issues

The default development image uses volumes to synchronise app code and
dependencies with the host machine. This may result in file ownership problems,
since the files must be read/writable by users both inside the container and the
host machine.

The user inside the container runs in a user group with id 1024. To ensure that
there are no permissions issues, you should ensure that your host machine also
has a user group with id 1024.

macOS:

```bash
dscl . -create /Groups/interop-devs
dscl . -create /Groups/interop-devs name interop-devs
dscl . -create /Groups/interop-devs passwd "*"
dscl . -create /Groups/interop-devs gid 1024
dscl . -create /Groups/interop-devs GroupMembership ${your-username}
```

Linux:

```bash
groupadd -g 1024 interop-devs
adduser ${your-username} interop-devs
```

Once the group is created, you may need to ensure that the source code is owned
and editable by this group:

```bash
chown -R :1024 src/
chmod -R 775 src/
chmod -R g+s src/
```

### Production builds

Using volumes for code synchronisation is convenient in development, but no good
for repeatable builds required in production. To build a production image, which
contains the app code and dependencies embedded inside the container image, run:

```bash
yarn build:prod
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

You can access the app at http://localhost:8084 and the mail catcher at
http://localhost:8086.

Default superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
