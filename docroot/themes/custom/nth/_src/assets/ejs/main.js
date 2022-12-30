import grid from './modules/grid';
import expand from './modules/expand';

const $ = window.$ || window.jQuery || {};

const ready = () => {

	console.log('hello ian 1.0.1');

	grid.init();
	expand.init();

	$(document).foundation();

};

window.addEventListener('DOMContentLoaded', ready, false);
