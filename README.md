# IanMoffitt.dev

Personal portfolio site.

## Init

`ddev start`

Serves CMS site at https://ianmoffitt.ddev.site.

`npm run serve`

Serves static Eleventy site at http://localhost:8080/.

## Pull DB

`ddev pull drush`

## Testing

`npm run test`

`ddev cypress-run --config-file cypress.drupal.config.js --spec "cypress/e2e/drupal.cy.js,cypress/e2e/drupal-authenticated.cy.js"`