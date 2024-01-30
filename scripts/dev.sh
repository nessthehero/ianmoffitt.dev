ls -la

git status

# Remove all unknown, non-ignored files
git clean -df

# Reset HEAD and discard any local changes to tracked files
git reset --hard

# NPM
npm cache clean
npm install

# Composer
composer install
