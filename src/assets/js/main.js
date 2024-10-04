import grid from './grid.js';
import spotify from './spotify.js';

const ready = () => {

    grid.init();
	spotify.init();

};

window.addEventListener('DOMContentLoaded', ready, false);
