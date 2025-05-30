const {defineConfig} = require('cypress');

module.exports = defineConfig({
	e2e: {
		setupNodeEvents(on, config) {
			// implement node event listeners here
		},
		baseUrl: 'https://ianmoffitt.ddev.site',
		supportFile: 'cypress/support/e2e.js'
	},
});
