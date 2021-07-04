FROM nginx:1.15.7-alpine
# timezone
ARG TZ
COPY ./docker/nginx/conf.d/ /etc/nginx/conf.d/
COPY ./docker/nginx/logs/ /etc/nginx/logs/
COPY ./laravel/ /var/www/laravel/
RUN apk update && apk --update add tzdata && \
  cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
  apk del tzdata && \
  rm -rf /var/cache/apk/*