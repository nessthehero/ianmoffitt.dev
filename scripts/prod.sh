#!/bin/bash

export PATH="$PATH"
export NVM_DIR=$HOME/.nvm;
source $NVM_DIR/nvm.sh;

# Versions of tools
whoami

which nvm

nvm use 18

node --version
npm --version
composer --version

# Show me all the files
ls -la

# View Git status
git status

# NPM
npm install --omit=dev

# Composer
composer install -q

# Drush deploy
drush status

drush cr

drush cim -v -y

drush cr

drush updb -v -y

drush deploy:hook

# Build static site
npm run prod


