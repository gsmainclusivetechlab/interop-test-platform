FROM node:14.5.0-stretch AS frontend
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm ci
COPY webpack.mix.js webpack.mix.js
COPY resources resources

VOLUME /usr/src/app/public/assets

CMD [ "/usr/local/bin/npm","run", "watch" ]
