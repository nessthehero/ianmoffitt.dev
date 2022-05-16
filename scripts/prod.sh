#!/bin/sh

rsync \
    -vrlD \
    --exclude=docroot/core \
    --exclude=vendor \
    --exclude=docroot/modules/contrib \
    --exclude=docroot/libraries \
    --exclude=docroot/sites/development.services.yml \
    --exclude=docroot/sites/default/files \
    --exclude=docroot/sites/default/settings.php \
    --exclude=docroot/sites/default/settings.local.php \
    --exclude=docroot/sites/default/settings.remote.php \
    --exclude=docroot/themes/custom/nth/_src \
    --delete \
    --progress \
    /home/ian/ianmoffitt.dev/current/ \
    /var/www/ianmoffitt.dev/
cd /var/www/ianmoffitt.dev
composer install
drush cr
drush cim
drush cr
