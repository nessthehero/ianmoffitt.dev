describe("Check pages in Drupal as an authenticated user.", () => {

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
		it("Check authenticated path: " + path, () => {
			cy.visit(path);
			cy.contains('Error').should('not.exist');
		});
	});

});