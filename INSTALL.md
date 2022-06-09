# ITP Install

This guide describes how to install the interoperability test platform in two modes:

- Installation using `install.sh` script => automated installation suitable for end-users. 

- Installation using the source code => manual compilation suitable for developers.


## Installation using `install.sh` script

### Install

1. Download the `install.sh` script from ITP Github repository.

    ```bash
    curl -o install.sh https://raw.githubusercontent.com/gsmainclusivetechlab/interop-test-platform/develop/install.sh
    chmod a+x install.sh
    ```

2. Run the `install.sh` script.

    ```bash
    ./install.sh interop
    ```

3. We the installation is done, you should see something like this:

    ```
    💻 The installation of the Interoperability Test Platform is finished! ✅

    Some information about your environment:
    🌐 interop console: http://localhost
    👤 username: superadmin@gsma.com
    🔑 password: <REDACTED>

    MySQL database and credential:
    🗄️ database: interop-db
    👤 username: interop-user
    🔑 password: <REDACTED>
    🔑 rootpass: <REDACTED>

    ➡️  More information about the project on GitHub Repository:
    🌐 https://github.com/gsmainclusivetechlab/interop-test-platform
    ```

### Management

1. See if ITP is running.
    
    ```bash
    cd interop
    docker-compose ps
    ```
    
    Result:
    
    ```bash
    Name                     Command                   State                                             Ports                                     
    -----------------------------------------------------------------------------------------------------------------------------------------------------
    interop_app_1       docker-php-entrypoint /usr ...   Up (healthy)     0.0.0.0:80->8080/tcp,:::80->8080/tcp, 0.0.0.0:443->8443/tcp,:::443->8443/tcp,9000/tcp
    interop_mailhog_1   MailHog                          Up               1025/tcp, 0.0.0.0:8086->8025/tcp,:::8086->8025/tcp
    interop_mysqldb_1   docker-entrypoint.sh mysqld      Up (healthy)     3306/tcp, 33060/tcp
    interop_queue_1     docker-php-entrypoint /usr ...   Up (unhealthy)   8080/tcp, 9000/tcp
    interop_redis_1     docker-entrypoint.sh redis ...   Up               6379/tcp
    ```

2. Start ITP.

    ```bash
    cd interop
    docker-compose up -d
    ```

    Result:

    ```bash
    Starting interop_mailhog_1 ... done
    Starting interop_mysqldb_1 ... done
    Starting interop_redis_1   ... done
    Starting interop_app_1     ... done
    Starting interop_queue_1   ... done
    ```

3. Stop ITP.

    ```bash
    cd interop
    docker-compose stop
    ```

    Result:

    ```bash
    Stopping interop_app_1     ... done
    Stopping interop_queue_1   ... done
    Stopping interop_mysqldb_1 ... done
    Stopping interop_redis_1   ... done
    Stopping interop_mailhog_1 ... done
    ```


### Uninstall

1. Removing ITP.

    ⚠️ This will remove ITP and all data.

    ```bash
    cd interop
    docker-compose down --rmi all -v
    cd .. && rm -rf interop
    ```


### Q/A and Troubleshooting

-------

Q: ❌ docker is required.

A: Install [Docker](https://docs.docker.com/engine/install/).

-------

Q: ❌ docker-compose is required.

A: Install [docker-compose](https://docs.docker.com/compose/install/).

-------

Q: ❌ mkdir: cannot create directory ‘interop’: File exists

A: You have started the [Install](#install) process already.

1) If the installation has succeeded, check if ITP is [Running](#management). If not, try to [Start ITP](#management).

2) If the installation has failed, try to [Uninstall](#uninstall) and the [Install](#install) ITP again.

-------

Q: Installation has failed for any reason.

A: Try to [Uninstall](#uninstall) and then [Install](#install) again.

-------
