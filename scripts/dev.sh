#!/bin/sh

export PATH="$PATH"

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
npm install

# Build static assets
npm run build

# Composer
composer install

# Drush deploy
# drush deploy
