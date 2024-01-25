import grid from './modules/grid';
import expand from './modules/expand';
import hljs from 'highlight.js';

const $ = window.$ || window.jQuery || {};

const ready = () => {

	console.log('hello ian 1.0.2');

	grid.init();
	expand.init();

	$(document).foundation();

	hljs.highlightAll();

};

window.addEventListener('DOMContentLoaded', ready, false);
