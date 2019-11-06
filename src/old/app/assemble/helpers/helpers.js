module.exports.register = function(Handlebars, options) {
	'use strict';

	Handlebars.registerHelper('replaceStr', function(haystack, needle, replacement) {

		if (haystack && needle) {
			return haystack.replace(needle, replacement);
		} else {
			return '';
		}
	});

	/* PARSE FIXTURE DATA
	 * @param path string - pass in the name of the JSON fixture file in assemble/fixtures/
	 * @options ??
	 *
	 */

	Handlebars.registerHelper('parseFixture', function(path, options) {
		if (!path || typeof path !== 'string') { return false; }

		var fs = require('fs');
		var nodePath = require('path');
		var fixture;

		path = nodePath.join(__dirname, '../fixtures/' + path);

		try {
			fixture = fs.readFileSync(path);
			fixture = fixture.toString('utf8');
			fixture = JSON.parse(fixture);
		} catch (err) {
			return console.error(err);
		}

		return options.fn(fixture);
	});

	Handlebars.registerHelper('log', function(data) {
		return console.log(data);
	});

	Handlebars.registerHelper('stringCompare', function(a, b, opts) {
		if (a == b) {
			return opts.fn(this);
		} else {
			return opts.inverse(this);
		}
	});

	Handlebars.registerHelper('toLowerCase', function(str) {
		return str.toLowerCase();
	});

	Handlebars.registerHelper('math', function(lvalue, operator, rvalue, options) {
		lvalue = parseFloat(lvalue);
		rvalue = parseFloat(rvalue);

		return {
			"+": lvalue + rvalue,
			"-": lvalue - rvalue,
			"*": lvalue * rvalue,
			"/": lvalue / rvalue,
			"%": lvalue % rvalue
		}[operator];
	});

	Handlebars.registerHelper('ifOr', function(a, b, opts) {
	    if (a || b) {
	        return opts.fn(this);
	    } else {
	        return opts.inverse(this);
	    }
	});

	Handlebars.registerHelper('ifAnd', function(a, b, opts) {
	    if (a && b) {
	        return opts.fn(this);
	    } else {
	        return opts.inverse(this);
	    }
	});

	Handlebars.registerHelper('svg', function(name) {
		return new Handlebars.SafeString("<svg class='icon icon-" + name + "'><use xlink:href='#icon-" + name + "'></use></svg>");
	});

	Handlebars.registerHelper('link', function(link) {
		function isValid(str) {
			return typeof str != 'undefined' && str != '';
		}

		var url = (isValid(link.url)) ? link.url : '#';
		var icon = (isValid(link.icon)) ? '{{svg "' + link.icon + '"}}' : '';
		var title = (isValid(link.title)) ? link.title : '';
		var style = (isValid(link.style)) ? ' class="' + link.style + '"' : '';
		var alt = (isValid(link.alt)) ? ' title="' + link.alt + '"' : ' title="' + title + '"';

		var link = '';

		if (url != '' && title != '') {
			link = '<a href="' + url + '"{0}{1}>{2}' + new Handlebars.SafeString(title) + '</a>';
			link = link.replace('{0}', alt);
			link = link.replace('{1}', style);
			link = link.replace('{2}', Handlebars.compile(icon));
		}

		return new Handlebars.SafeString(link);
	});

	Handlebars.registerHelper('place', function(w, h, text) {
		function isValid(str) {
			return typeof str != 'undefined' && str != '' && typeof str.data == 'undefined';
		}

		var width = (isValid(w)) ? w : '300';
		var height = (isValid(h)) ? 'x' + h : '';
		var text = (isValid(text)) ? '?text=' + encodeURI(text) : '';

		var url = 'http://placehold.it/' + width + height + text;

		return new Handlebars.SafeString("<img src='" + url + "' alt='Placeholder Image' />")

	});

};
