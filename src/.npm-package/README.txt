Mounting a single file (such as package.json) into a container using
docker-compose volumes makes it impossible to update that file from within the
container. To work around this, these files are placed inside a separate
directory, which can be mounted without these problems.
