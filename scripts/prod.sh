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
npm install --production

# Directory ownership
chown -R ian dist
chgrp -R ian dist

# Build static site
npm run prod

# Composer
composer install -q

# Drush deploy
drush deploy
