FROM nginx:alpine

RUN apk --update --no-cache add \
    vim \
    bash

COPY public /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]