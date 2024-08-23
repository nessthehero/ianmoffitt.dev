const markdownIt = require("markdown-it");
const inspect = require("util").inspect;

module.exports = function(eleventyConfig) {

	// Templating
	eleventyConfig.setTemplateFormats("html,njk,pug");

    // Pug
    // eleventyConfig.setPugOptions({ debug: true });

    // Assets
    eleventyConfig.addPassthroughCopy("src/assets/**/*.*");

    // Watch
    eleventyConfig.addWatchTarget("./src/_includes/**/*.njk");
    eleventyConfig.addWatchTarget("./src/_includes/**/*.pug");
    eleventyConfig.addWatchTarget("./src/assets/css/**/*.css");

    // Global Data
    eleventyConfig.addGlobalData("getYear", () => new Date().getFullYear());

    // Markdown
    let mdOptions = {
        html: true,
        breaks: true,
        linkify: true
    };

    eleventyConfig.setLibrary("md", markdownIt(mdOptions));

	const md = new markdownIt({
		html: true
	});
11
	eleventyConfig.addPairedShortcode("markdown", (content) => {
		return md.render(content);
	});

	eleventyConfig.addFilter("debug", (content) => `<pre>${inspect(content)}</pre>`);
	eleventyConfig.addFilter("date", require("./src/filters/date.js"));

    return {
		markdownTemplateEngine: 'njk',
		dataTemplateEngine: 'njk',
		htmlTemplateEngine: 'njk',
        dir: {
			input: "src",
            output: "dist"
        }
    }

};
