#!/usr/bin/env bash 

echo $PRIVATE_KEY > /var/www/config/secrets/prod/prod.decrypt.private.php

composer install -n 

exec "$@"