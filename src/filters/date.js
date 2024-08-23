const {
	DateTime
} = require("luxon");

// Add a friendly date filter to nunjucks.
// Defaults to format of LLLL dd, yyyy unless an alternate is passed as a parameter.
// {{ date | friendlyDate('OPTIONAL FORMAT STRING') }}
// List of supported tokens: https://moment.github.io/luxon/docs/manual/formatting.html#table-of-tokens

module.exports = (dateObj, format = 'LLLL dd, yyyy') => {
	if (dateObj instanceof Date) {
		return DateTime.fromJSDate(dateObj, {
			zone: 'utc',
			locale: "en"
		}).toFormat(format);
	} else {
		return DateTime.fromISO(dateObj, {
			zone: "utc",
			locale: "en"
		}).toFormat(format);
	}
};