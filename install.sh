#!/bin/bash
set -u

# Environment
# colors
NC='\033[0m'
RED='\033[0;31m'
GREEN='\033[0;32m'

# naming
PLATFORM_LONGNAME=
PLATFORM_NAME=
PROJECT_DOMAIN='www.example.com'
DBNAME=
DBUSER=
DBPASS=
DBROOTPASS=
SUPERADMIN_PASS=

# Features
COMPLIANCE_ENABLED=
FAQ_ENABLED=
PLUGIN_ENABLED=

# command path
DOCKER_COMPOSE_CLI=$(which docker-compose)
DOCKER_CLI=$(which docker)
CURL_CLI=$(which curl)

# Functions
# print
print() {
  echo -e "$@"
}

# print_r
print_r() {
  echo -ne "$@"
}

# abort
abort() {
  echo -e "$@"
  echo -e ""
  echo -e "Please refer to https://.../ for detailed install and troubleshoot."
  exit 1
}

# abort_r
abort_r() {
  echo -ne "$@"
  echo -e ""
  echo -e "Please refer to https://.../ for detailed install and troubleshoot."
  exit 1
}

# generate random passwords
randpw() {
  < /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c${1:-16}; echo;
}

# app.env
create_app_env() {
  cat <<EOF > ./${PLATFORM_NAME}/app.env
PROJECT_DOMAIN=${PROJECT_DOMAIN}

## Read by PHP services
DB_HOST=mysqldb
DB_DATABASE=${DBNAME}
DB_USERNAME=${DBUSER}
DB_PASSWORD=${DBPASS}

# SMTP Configuration
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=trap
MAIL_PASSWORD=trap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=support@${PROJECT_DOMAIN}
MAIL_FROM_NAME="${PLATFORM_LONGNAME}"

# Platform Settings
## Set to 'true' to make Invitation codes mandatory for registration
INVITATION_REQUIRED=true

## Invitation code expiry in seconds
INVITATION_LIFETIME=432000

### Set a limit on the maximum number of attempts for any test case during a compliance session
COMPLIANCE_SESSION_EXECUTION=3

# Environment Settings

APP_DEBUG=true
APP_ENV=production
APP_KEY=
APP_URL=https://${PROJECT_DOMAIN}
APP_GOOGLE_ANALYTICS=
# Optionally, it's possible to serve the frontend and testing URLs on different domains
TESTING_URL_HTTP=http://${PROJECT_DOMAIN}
TESTING_URL_HTTPS=https://${PROJECT_DOMAIN}

SESSION_TEST_AVAILABLE=true
SESSION_TEST_QUESTIONNAIRE_AVAILABLE=true
SESSION_COMPLIANCE_AVAILABLE=${COMPLIANCE_ENABLED}

# How long should a test run wait for the first message to be sent
TESTRUN_INITIAL_TIMEOUT=300
# How long should a test run wait for subsequent messages to be sent
TESTRUN_STEP_TIMEOUT=30
# How often to check if a test run is complete
TESTRUN_TIMEOUT_FREQUENCY=30

# When a test run is in progress, force all incoming messages to match against it 
FORCE_SEQUENTIAL_TESTS=true

# When there are no active test runs and we receive a message which matches a test case, should we create a new test run?
CREATE_TESTRUN_ON_MATCH=true

# Max json number of key-value pairs that will be processed with vue-json-pretty
JSON_PRETTY_MAX_SIZE=500

# Features disable/enable
FEATURE_FAQ=${FAQ_ENABLED}
FEATURE_SIMULATOR_PLUGIN=${PLUGIN_ENABLED}

EOF
}

# mysql.env
create_mysql_env() {
  cat <<EOF > ./${PLATFORM_NAME}/mysql.env
## Read by mysqldb services
MYSQL_DATABASE=${DBNAME}
MYSQL_USER=${DBUSER}
MYSQL_PASSWORD=${DBPASS}
MYSQL_ROOT_PASSWORD=${DBROOTPASS}
EOF
}

# docker-compose.yml
create_docker_compose_yml() {
  cat <<EOF > ./${PLATFORM_NAME}/docker-compose.yml
version: '3.7'

x-common-php: &common-php
  image: gsmainclusivetechlab/interop-test-platform:develop
  restart: always
  environment:
    PROJECT_DOMAIN: ${PROJECT_DOMAIN}
  env_file: app.env
  depends_on:
    - mysqldb
    - redis
    - mailhog
  security_opt:
    - apparmor:unconfined
  cap_add:
    - SYS_PTRACE
  volumes:
    - certbot_certs:/etc/nginx/ssl/letsencrypt
    - certbot_www:/var/www/certbot
    - storage:/var/www/html/storage/app

services:
  app:
    <<: *common-php
    ports:
      - '80:8080'
      - '443:8443'
    environment:
      CONTAINER_ROLE: app
      WAIT_HOSTS: mysqldb:3306
      WAIT_SLEEP_INTERVAL: 5
      WAIT_HOSTS_TIMEOUT: 100

  queue:
    <<: *common-php
    environment:
      CONTAINER_ROLE: queue

  mysqldb:
    image: mysql:8
    restart: always
    env_file: mysql.env
    healthcheck:
      retries: 10
      test: [CMD, mysqladmin, ping, -h, localhost]
      timeout: 20s
    volumes:
      - mysqldata:/var/lib/mysql:rw

  redis:
    image: redis:5
    restart: always
    environment:
      REDIS_DISABLE_COMMANDS: FLUSHDB,FLUSHALL
    volumes:
      - redisdata:/data:rw

  mailhog:
    image: mailhog/mailhog
    restart: always
    ports:
      - '8086:8025'

volumes:
  mysqldata: {}
  redisdata: {}
  ca_certs: {}
  certbot_certs: {}
  certbot_www: {}
  storage: {}
EOF
}

# Checking

# bash
#if [ -z "${BASH_VERSION:-}" ]
#then
#  abort "Bash is required to interpret this script."
#fi

# operating system
OS="$(uname)"
if [[ "${OS}" == "Linux" ]]
then
  ITP_ON_LINUX=1
elif [[ "${OS}" != "Darwin" ]]
then
  abort "Interop Test Platform is only supported on macOS and Linux."
fi

# command line arguments
if [[ "$#" -ne 1 ]]
then
  echo -e "Usage: $0 <platform>"
  echo -e "<platform> can be:"
  echo -e "  'interop'    => Interoperability Test Platform"
  echo -e "  'compliance' => Compliance Platform"
  exit 1
fi

# validating platform name
PLATFORM_NAME=$1
case ${PLATFORM_NAME} in
  interop)
    PLATFORM_NAME="interop"
    PLATFORM_LONGNAME="Interoperability Test Platform"
    COMPLIANCE_ENABLED="false"
    FAQ_ENABLED="false"
    PLUGIN_ENABLED="false"
    ;;
  compliance)
    PLATFORM_NAME="compliance"
    PLATFORM_LONGNAME='Compliance Platform'
    COMPLIANCE_ENABLED='true'
    FAQ_ENABLED='true'
    PLUGIN_ENABLED='true'
    ;;
  *)
    abort "❌ invalid platform name: ${RED}${PLATFORM_NAME}${NC}"
    ;;
esac

# setting database values based on the platform
DBNAME="${PLATFORM_NAME}-db"
DBUSER="${PLATFORM_NAME}-user"
DBPASS=$(randpw)
DBROOTPASS=$(randpw)
SUPERADMIN_PASS=$(randpw)

# Starting
print "➡️  GSMA Inclusive Tech Lab - https://www.gsma.com/lab\n"

# checking for docker requirements
print "⚙️  Checking for requirements"
if [ ! -z $DOCKER_CLI ]; then
    DOCKER_VERSION=`$DOCKER_CLI version |grep ^" Version" | tr -s ' ' | cut -d" " -f3`
    DOCKER_SWARM_MODE=`$DOCKER_CLI info |grep ^" Swarm" | cut -d" " -f3`
fi
if [ ! -z $DOCKER_COMPOSE_CLI ]; then
    DOCKER_COMPOSE_VERSION=`$DOCKER_COMPOSE_CLI version --short`
fi
if [ ! -z $CURL_CLI ]; then
    CURL_VERSION=`curl --version | head -n 1 | awk '{ print $2 }'`
fi

if [ -z "${DOCKER_VERSION:-}" ]
then
    abort "❌ ${RED}docker is required.${NC}"
fi
if [ -z "${DOCKER_COMPOSE_VERSION:-}" ]
then
    abort "docker-compose is required.${NC}"
fi
if [ $DOCKER_SWARM_MODE == "active" ]
then
    abort "❌ ${RED}install cannot proceed in docker swarm mode.${NC}"
fi
if [ -z "${CURL_VERSION:-}" ]
then
    abort "curl is required.${NC}"
fi
print "✅ docker: ${GREEN}${DOCKER_VERSION}${NC}"
print "✅ docker-compose: ${GREEN}${DOCKER_COMPOSE_VERSION}${NC}"
print "✅ curl: ${GREEN}${CURL_VERSION}${NC}"

print ""

# installation of the platform
print "⚙️  Installing ${GREEN}${PLATFORM_LONGNAME}${NC}"

# local folder
CMD_OUTPUT=$(mkdir ${PLATFORM_NAME} 2>&1)
if [ "$?" -ne 0 ]; then
    abort "❌ ${RED}${CMD_OUTPUT}${NC}"
else
    print "✅ creating install folder => ${GREEN}./${PLATFORM_NAME}${NC}"
fi

# app.env
create_app_env
print "✅ creating app.env => ${GREEN}./${PLATFORM_NAME}/app.env${NC}"

# mysql.env
create_mysql_env
print "✅ creating mysql.env => ${GREEN}./${PLATFORM_NAME}/mysql.env${NC}"

# downloading docker-compose.yml
create_docker_compose_yml
print "✅ creating docker-compose.yml => ${GREEN}./${PLATFORM_NAME}/docker-compose.yml${NC}"

print ""

# Configuring
print "⚙️  Configuring ${GREEN}${PLATFORM_LONGNAME}${NC}"
cd ${PLATFORM_NAME}

# downloading images
print_r "✅ docker images => ${GREEN}downloading... ${NC}\\r"
CMD_OUTPUT=$(${DOCKER_COMPOSE_CLI} pull -q 2>&1)
if [ "$?" -ne 0 ]; then
  abort_r "❌ ${RED}${CMD_OUTPUT}${NC}\\n"
else
  print_r "✅ docker images => ${GREEN}downloading... done!${NC}\\n"
fi

# setting APP_KEY
print_r "✅ app key => ${GREEN}generating... ${NC}\\r"
CMD_OUTPUT=$(docker-compose run app sh -c "echo APP_KEY= >> .env; php artisan key:generate -q; cat .env | grep ^APP_KEY" 2>/dev/null)
if [ -z "${CMD_OUTPUT}" ]; then
  abort_r "❌ app key => ${RED}generating... failed!${NC}\\n"
else
  CMD_OUTPUT=`sed -i "s#APP_KEY=#$CMD_OUTPUT#" app.env`
  if [ "$?" -ne 0 ]; then
    abort_r "❌ app key => ${RED}generating... failed!${NC}\\n"
  else
    print_r "✅ app key => ${GREEN}generating... done!${NC}\\n"
  fi
fi

# seeding database
print_r "✅ database => ${GREEN}updating... ${NC}\\r"
CMD_OUTPUT=$(docker-compose run app sh -c "sed -i \"s/('[^']*'/(\'${SUPERADMIN_PASS}\'/g\" ./database/seeders/UsersTableSeeder.php && /wait && php artisan migrate:refresh --seed --force -q" > /dev/null 2>&1)
if [ "$?" -ne 0 ]; then
  abort_r "❌ database => ${RED}updating... failed!${NC}\\n"
else
  print_r "✅ database => ${GREEN}updating... done!${NC}\\n"
fi

# starting app
print_r "✅ ${PLATFORM_NAME} => ${GREEN}starting... ${NC}\\r"
CMD_OUTPUT=$(docker-compose up -d --force-recreate 2>/dev/null)
if [ "$?" -ne 0 ]; then
  abort_r "❌ ${PLATFORM_NAME} => ${RED}starting... failed!${NC}\\n"
else
  print_r "✅ ${PLATFORM_NAME} => ${GREEN}starting... done!${NC}\\n"
fi

# finalising
print ""
print "💻 The installation of the ${GREEN}${PLATFORM_LONGNAME}${NC} is finished! ✅"
print ""
print "Some information about your environment:"
print "🌐 ${PLATFORM_NAME} console: http://localhost"
print "👤 username: superadmin@gsma.com"
print "🔑 password: ${SUPERADMIN_PASS}"
print ""
print "MySQL database and credential:"
print "🗄️  database: ${DBNAME}"
print "👤 username: ${DBUSER}"
print "🔑 password: ${DBPASS}"
print "🔑 rootpass: ${DBROOTPASS}"
print ""
print "➡️  More information about the project on GitHub Repository:"
print "🌐 https://github.com/gsmainclusivetechlab/interop-test-platform"
print ""