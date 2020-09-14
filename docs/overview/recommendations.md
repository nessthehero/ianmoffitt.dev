# Recommendations and Watch outs

## Acquia

### Set cron to "Never" in Drupal and instead use Scheduled Jobs to run cron externally.

Setting cron to an interval makes it run as part of the webpage request. If you are performing a task that takes a significant portion of time, the user visiting the page will have to wait for your task to complete. 

Using Scheduled Jobs runs the cron task as a background process on the server, and can be run as frequently as you like.

[Setting up a Scheduled Job](/supplemental/acquia?id=creating-a-scheduled-job-in-acquia)

## Composer

### Avoid updating all modules at once

Try to avoid running `composer update` on its own when updating modules. This can update unexpected modules or dependencies, and could break the installation or prevent future updates. It's completely beyond the scope of this guide to troubleshoot or aid in composer issues, so tread lightly.

Instead, update each module specifically. 

`composer update drupal/projectname`

The only composer assistance this guide can offer is to:
1. Back up very frequently, and before any updates.
2. Delete the composer.lock file and vendor directory if the module refuses to download or install.

### Acquia Search

Many modules used to support Acquia Site Search have specific versions as dependencies and cannot be updated. It's beyond the scope of this guide to document this, but take care when updating any modules that affect search.
