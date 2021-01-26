To avoid duplication between development and production images, I have
separated out the shared components into split files inside ./parts.

Docker doesn't have an "include" functionality to recombine them,
so I have added a script to create a "full" Dockerfile based on the parts.
Call it with:

./Dockerfiles/make.sh

(or use the package.json script to do the same with `yarn prepare:docker`)