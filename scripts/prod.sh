#!/bin/sh

rsync \
    -vrlD \
    --exclude=docroot/core \
    --exclude=vendor \
    --exclude=docroot/modules/contrib \
    --exclude=docroot/themes/contrib \
    --exclude=docroot/libraries \
    --exclude=docroot/sites/development.services.yml \
    --exclude=docroot/sites/default/files \
    --exclude=docroot/themes/custom/nth/_src \
    --exclude=drush/drush.yml \
    --delete \
    --progress \
    /home/ian/ianmoffitt.dev/current/ \
    /var/www/ianmoffitt.dev/
cd /var/www/ianmoffitt.dev

composer install

drush cr

drush cim --yes
drush updb --yes

drush cr
