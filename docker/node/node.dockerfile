FROM node:current-alpine
ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}

RUN addgroup -g ${GID} laravel \
 && adduser -u ${UID} -G laravel -s /bin/sh -D laravel \

WORKDIR /app
COPY package.json .
RUN npm install
COPY . .
USER laravel
CMD [ "npm", "build"]
