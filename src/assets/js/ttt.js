const ttt = (s, d) => {
	var p;
	for (p in d) {
		if (d.hasOwnProperty(p)) {
			s = s.replace(new RegExp('{' + p + '}', 'g'), d[p]);
		}
	}
	return s;
};

export default ttt;