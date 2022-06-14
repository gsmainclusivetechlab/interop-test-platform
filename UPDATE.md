# Updating the Interop Test Platform Installation

This guide shows how to update an ITP installation to the latest version and it was written considering that the ITP installation was done following the [INSTALL.md](INSTALL.md) guide.

### Locate the ITP install folder

You can find the ITP installation inside the folder `interop`. You should locate this folder in your deployment server and it must contain at least the following files: `docker-compose.yml` `app.env` `mysql.env`

### Update ITP Docker Image

Access the `interop` folder and pull the new ITP image:

```bash
cd interop
docker pull gsmainclusivetechlab/interop-test-platform:develop
```

### Restart ITP

After the latest ITP image is pulled from DockerHub, restart the ITP:

```bash
docker-compose up -d --force-recreate
```

#### Output
```bash
Recreating interop_mysqldb_1 ... done
Recreating interop_redis_1   ... done
Recreating interop_mailhog_1 ... done
Recreating interop_queue_1   ... done
Recreating interop_app_1     ... done
```

### Check if ITP is Running

Check if all the containers are up and running:

```bash
docker-compose ps
```

#### Output
```bash
interop_app_1       docker-php-entrypoint /usr ...   Up (healthy)     0.0.0.0:80->8080/tcp,:::80->8080/tcp,0.0.0.0:443->8443/tcp,:::443->8443/tcp, 9000/tcp
interop_mailhog_1   MailHog                          Up               1025/tcp, 0.0.0.0:8086->8025/tcp,:::8086->8025/tcp
interop_mysqldb_1   docker-entrypoint.sh mysqld      Up (healthy)     3306/tcp, 33060/tcp
interop_queue_1     docker-php-entrypoint /usr ...   Up (unhealthy)   8080/tcp, 9000/tcp
interop_redis_1     docker-entrypoint.sh redis ...   Up               6379/tcp
```

### Access ITP via Browser

Now you can access the ITP URL in your browser and log in.

