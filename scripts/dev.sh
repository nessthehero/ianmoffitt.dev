#!/bin/bash

#export PATH="$PATH"
#export NVM_DIR=$HOME/.nvm;
#source $NVM_DIR/nvm.sh;

# Versions of tools
whoami

which nvm

#nvm use 18

node --version
npm --version
composer --version

# Show me all the files
ls -la

# View Git status
git status

# NPM
npm install

# Build static assets
npm run build

# Deploy static assets
npm run deploy

# Composer
composer install

# Drush deploy
# drush deploy
