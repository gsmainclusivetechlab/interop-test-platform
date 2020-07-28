FROM node:14.5.0-stretch AS frontend
RUN mkdir -p /usr/src/app && chown -R node:node /usr/src/app
USER node
WORKDIR /usr/src/app
# We'll place our files in npm-package
# Create that folder, place empty place holders in there,
#   and create a link to them
RUN mkdir ./npm-package
COPY --chown=node:node ./npm-package ./npm-package
RUN ln -s ./npm-package/package.json ./package.json && \
	ln -s ./npm-package/package-lock.json ./package-lock.json
RUN ls -la
RUN npm install
COPY webpack.mix.js webpack.mix.js
COPY resources resources

VOLUME /usr/src/app/public/assets

CMD [ "/usr/local/bin/npm","run", "watch" ]
