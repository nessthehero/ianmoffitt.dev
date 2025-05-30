import cy from "mocha/lib/interfaces/exports";

describe("Check pages in Drupal as an authenticated user.", () => {

	// TODO: Create a post here?

	beforeEach(() => {
		cy.login('tester', 'zzzTester');
	});

	[
		'/',
		'/user',
		'/admin/reports/status',
		'/admin/content',
		'/node/add/post'
	].forEach((path) => {
		it("Check authenticated path: " + Cypress.config('baseUrl') + path, () => {
			cy.visit(Cypress.config('baseUrl') + path);
			cy.contains('Error').should('not.exist');
		});
	});

});