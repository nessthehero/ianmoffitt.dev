#!/bin/bash

git -C /var/www/dev.ianmoffitt.dev fetch origin --depth=5

git -C /var/www/dev.ianmoffitt.dev clean -df

git -C /var/www/dev.ianmoffitt.dev reset --hard

git -C /var/www/dev.ianmoffitt.dev checkout "$1"

cd /var/www/dev.ianmoffitt.dev/ && /usr/bin/bash scripts/dev.sh
