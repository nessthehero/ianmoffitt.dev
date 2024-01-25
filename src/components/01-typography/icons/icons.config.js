'use strict';

const fs = require('fs');

let icons = null;
const siteIcons = [];

if (fs.existsSync('../../../assets/scss/icons/selection.json')) {

	icons = require('../../../assets/scss/icons/selection.json');

	if (typeof icons.icons !== 'undefined') {
		icons.icons.forEach(icon => {
			siteIcons.push(icon.properties.name);
		});
	}

}

module.exports = {
	"title": "Icons",
	"status": "ready",
	"context": {
		"icons": siteIcons
	}
}
