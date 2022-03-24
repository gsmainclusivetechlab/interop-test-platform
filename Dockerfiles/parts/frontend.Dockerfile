# Build frontend assets
FROM node:14.14.0-stretch AS frontend
WORKDIR /usr/src/app
COPY package.json ./package.json
COPY package-lock.json ./package-lock.json
RUN npm ci
COPY webpack.mix.js webpack.mix.js
COPY resources resources
RUN npm run prod
