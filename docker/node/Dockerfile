FROM node:14.6.0-alpine
RUN mkdir -p /var/www/frontApp
# timezone
ARG TZ
WORKDIR /var/www/frontApp
COPY ./frontApp/ /var/www/frontApp/

RUN apk update && \
    apk upgrade && \
    apk add --update-cache --no-cache make gcc g++ python && \
    yarn install && yarn cache clean --force

EXPOSE 3000

ENTRYPOINT ["yarn", "run", "dev"]
