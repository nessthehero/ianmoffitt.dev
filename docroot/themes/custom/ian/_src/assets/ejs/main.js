import grid from './modules/grid';

const $ = window.$ || window.jQuery || {};

const ready = () => {

	console.log('hello ian 1.0.1');

	grid.init();

	$(document).foundation();

};
window.addEventListener('DOMContentLoaded', ready, false);
