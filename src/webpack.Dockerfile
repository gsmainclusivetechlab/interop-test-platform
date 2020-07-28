FROM node:14.5.0-stretch AS frontend
RUN mkdir -p /usr/src/app && chown -R node:node /usr/src/app
USER node
WORKDIR /usr/src/app
# We'll place our files in npm-package and mount a volume there. This is messy
# but it avoids problems with npm being unable to write to a single-mounted file
RUN mkdir ./npm-package
COPY --chown=node:node ./package.json ./npm-package/package.json
COPY --chown=node:node ./package-lock.json ./npm-package/package-lock.json
RUN ln -s ./npm-package/package.json ./package.json && \
	ln -s ./npm-package/package-lock.json ./package-lock.json
RUN npm install
COPY webpack.mix.js webpack.mix.js
COPY resources resources

VOLUME /usr/src/app/public/assets

CMD [ "/usr/local/bin/npm","run", "watch" ]
