FROM nginx:alpine

RUN apk --update --no-cache add \
    vim \
    bash

COPY --chown=www-data:www-data public /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]