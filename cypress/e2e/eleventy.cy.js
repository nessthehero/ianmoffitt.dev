describe("Check pages in Eleventy.", () => {

	[
		'/',
		'/posts'
	].forEach((path) => {
		it("Check path: " + Cypress.config('baseUrl') + path, () => {
			cy.visit(Cypress.config('baseUrl') + path);
		});
	});

});