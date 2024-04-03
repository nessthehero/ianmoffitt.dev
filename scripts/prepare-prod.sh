#!/bin/bash

git -C /var/www/ianmoffitt.dev fetch origin --depth=5

git -C /var/www/ianmoffitt.dev clean -df

git -C /var/www/ianmoffitt.dev reset --hard

git -C /var/www/ianmoffitt.dev pull

cd /var/www/dev.ianmoffitt.dev && /usr/bin/bash scripts/prod.sh

