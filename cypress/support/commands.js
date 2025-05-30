// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

Cypress.Commands.add('login',
    /**
     * Logs in user
     *
     * @method login
     * @param {string} username - Username of user.
     * @param {string} password - Password of user.
     * @example cy.login();
     */
    (username, password) => {
        cy.session([username, password], () => {
            cy.visit('/user/login');
            cy.get('#edit-name').type(username);
            cy.get('#edit-pass').type(password);
            cy.get('#edit-submit').click();
            cy.contains(username).should('exist');
        });
        cy.log(cy.session);
    });