#!/bin/bash
set -me

APP_ENV=$1

/usr/local/bin/docker-php-entrypoint

php-fpm

fg %1