describe("Check pages in Drupal as an anonymous user.", () => {

	[
		'/',
		'/now'
	].forEach((path) => {
		it("Check path: " + path, () => {
			cy.visit(path);
			cy.contains('Error').should('not.exist');
		});
	});

});