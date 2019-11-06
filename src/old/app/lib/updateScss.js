var fs = require('fs');
var path = require('path');
var nodeDir = require('node-dir');
var collection = [
	{
		'name': 'partials',
		'searchName': 'partials',
		'dir': './app/assemble/partials'
	},
	{
		'name': 'modules',
		'searchName': 'modules',
		'dir': './app/assemble/modules'
	},
	{
		'name': 'templates',
		'searchName': 'assemble',
		'dir': './app/assemble'
	}
];

collection.forEach(function(data) {

	nodeDir.files(data.dir, function(err, files) {
		var names = [];
		var finalScssFile = '';
		var finalPath = './app/sass/' + data.name + '/_assemble-' + data.name +'.scss';

		if (err) {
			throw err;
		}

		console.log('Updating Assemble.io ' + data.name + ' sass...');

		files.forEach(function(entry) {

			if (path.extname(entry) === '.hbs') {
				// Add names to be added to .scss file
				var regex = new RegExp('^.+' + data.searchName + '/');
				var name = path.basename(entry, '.hbs');

				if (!/^_+/.test(name)/* && data.name !== 'templates'*/) {
					name = '_' + name;
				}

				entry = entry.replace(regex, '');
				entry = entry.split('/');
				entry[entry.length - 1] = name;

				if (data.name === 'templates') {
					if (entry.length === 1 && entry[0] !== 'index') {
						names.push(entry);
						writeMissingFiles(data, entry);
					}
				} else {
					entry = entry.join('/');
					names.push(entry);
					writeMissingFiles(data, entry);
				}
			}
		});

		names.forEach(function(name) {
			var importPath = '@import \'';

			importPath = importPath + name;
			finalScssFile = finalScssFile + importPath + '\';\n';
		});

		// console.log(finalPath, finalScssFile);

		if (finalScssFile == '') {
			console.log('file is empty!!!');
		}
		fs.writeFile(finalPath, finalScssFile, function(err) {
			if (err) { throw err; }

			console.log('Done! ' + data.name + ' updated!');
		});

	});
});

// Check to see if same name .scss file exists. If not, create one
var writeMissingFiles = function(data, entry) {

	// console.log("\n====\nwriteMissingFiles()\n\n" + data + "\n" + entry + "\n====\n");

	var name = entry;
	var filename = data.name + '/' + name +'.scss'
	var readPath = './app/sass/' + filename;
	fs.readFile(readPath, 'utf8', function(err, file) {

		// console.log("reading file " + readPath);

		if (err) {
			console.log('\n\nA SASS file doesnt exist. Creating ' + filename + ' for you.\n');
		}

		if (!file && name != null && typeof name !== 'undefined') {

			// console.log('checking name: ', name, typeof name, name.length);

			if (typeof name === "object") { name = name[0]; }

			var writePath = readPath;
			var cleanName = name.replace('_', '');
			var content = '.' + cleanName + ' {\n\n}\n';

			// console.log('== no file contents ==');

			if (cleanName.length >= 1) {
				fs.writeFile(writePath, content, function(err) {

					if (err) {
						throw err;
					}

					console.log('\nI just wrote ' + filename + ' for you!\n');

				});
			}

		}
	});
};
