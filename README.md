## Interoperability Test Platform

[![Codacy grade](https://img.shields.io/codacy/grade/8ff2b7590e13431dad7032a973d908fd?logo=codacy)](https://www.codacy.com/gh/gsmainclusivetechlab/interop-test-platform?utm_source=github.com&utm_medium=referral&utm_content=gsmainclusivetechlab/interop-test-platform&utm_campaign=Badge_Grade)

[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/master?label=Master&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=master)
[![CircleCI](https://img.shields.io/circleci/build/github/gsmainclusivetechlab/interop-test-platform/develop?label=Develop&logo=circleCI&token=7cc80f8c435154849e1f57a8708d8765da9ffa1a)](https://app.circleci.com/pipelines/github/gsmainclusivetechlab/interop-test-platform?branch=develop)

### Installation

Project can be setup with Docker.

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

Access your site via URL: <http://localhost:8084>

Mail catcher: <http://localhost:8086>

Superadmin login: superadmin@gsma.com / qzRBHEzStdG8XWhy
