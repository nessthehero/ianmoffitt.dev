var exec = require('child_process').exec;
function execute(command, callback){
	exec(command, function(error, stdout, stderr){ callback(stdout); });
};

let t = '';
console.log('---');

execute('drush status --field=uri', function (data) {
	t = data;
	console.log(data);
});

console.log(t);

function pullInSitemap(url) {



}
