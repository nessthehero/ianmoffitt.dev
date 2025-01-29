describe("Check pages in Eleventy.", () => {

	[
		'/',
		'/now'
	].forEach((path) => {
		it("Check path: " + path, () => {
			cy.visit(path);
		});
	});

});