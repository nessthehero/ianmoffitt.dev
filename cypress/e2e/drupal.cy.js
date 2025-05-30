describe("Check pages in Drupal as an anonymous user.", () => {

	[
		'/',
	].forEach((path) => {
		it("Check path: " + Cypress.config('baseUrl') + path, () => {
			cy.visit(Cypress.config('baseUrl') + path);
			cy.contains('Error').should('not.exist');
		});
	});

});