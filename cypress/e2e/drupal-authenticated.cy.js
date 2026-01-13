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
			// Might need to do more here.
			cy.request(Cypress.config('baseUrl') + path).then((response) => {
				expect(response.status).to.not.equal(500);
			});
		});
	});

});
