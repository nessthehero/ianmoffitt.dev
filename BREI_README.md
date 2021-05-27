# Client Name

Settings file is located at `/docroot/sites/default/settings.php`. You may need to save a copy of `default.settings.php` as `settings.php`, as this file may be ignored by Git. Otherwise, you may need to update the file with your local database settings.

Super user:

admin / super_user_password

Please only use the super user account to create accounts for yourself, and use your own administrator account to create content or make changes to the CMS.

## Local debugging

You can turn on local debugging by saving a copy of `/docroot/sites/example.settings.local.php` under default as `settings.local.php`.

This will reference the `development.services.yml` file which has template debugging turned off and cache disabled. Turning on template debugging may cause unwanted markup to appear, breaking some background image features.

Here are recommended settings for `development.services.yml`:

```yaml
# Local development services.
#
# To activate this feature, follow the instructions at the top of the
# 'example.settings.local.php' file, which sits next to this file.
parameters:
  http.response.debug_cacheability_headers: true
  twig.config:
    cache: false
    auto_reload: true
    debug: true
services:
  cache.backend.null:
    class: Drupal\Core\Cache\NullBackendFactory
```

## Init composer on fresh project

`composer install`

## Clear cache

Drush 9: `drush cr`

## Back up site (without caching tables)

Drush 9: `drush sql:dump --skip-tables-list=cache,cache_* > name-of-backup-file.sql`

## Restore site

Drush 9: `drush sql:cli < name-of-backup-file.sql`

## Update site and modules

`composer update`

**NOTE:** Exercise caution when updating all composer packages, make backups of the database and make sure the latest code is checked in *before* you run the command.

## Install drupal module

Project name is same as the url slug of the drupal project.

`composer require drupal/nameofproject`

## Update drupal module

`composer update drupal/nameofproject`

## Static Project

Uncompiled static project located at `/docroot/themes/THEMEFOLDER/_src`

Run `npm install` from that directory to initialize.

See README in that directory for system requirements and more instructions.