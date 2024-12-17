/**
 * Spotify Now Playing
 * @module components/spotify
 */

import ttt from './ttt.js';

const spotify = {

	$el: document.getElementById('spotify'),

	endpoint: '',

	interval: null,

	playingKey: '',

	template: `
<div class="spotify__player">
	<div class="spotify__art">
		<a href="{songlink}" target="_blank"><img src="{imgsrc}" alt="{imgalt}" /></a>
	</div>
	<div class="spotify__song">
		<h3 class="spotify__header" id="spotify-header">{header}</h3>	
		<div class="spotify__info">
			<h4 class="spotify__title">{title}</h4>
			<h5 class="spotify__artist">{artist}</h5>
			<h6 class="spotify__album">{imgalt}</h6>
		</div>
		<div id="spotify-progress" class="spotify__progress" style="--animation-width: {aw}; --animation-length: {al}; --animation-state: {state}"></div>
	</div>
</div>
`,

	init() {

		if (this.$el) {
			this.endpoint = this.$el.getAttribute('data-endpoint');
			this.getNowPlaying();
			this.interval = setInterval(this.getNowPlaying.bind(this), 10000);
		}
	},

	getNowPlaying() {

		fetch(this.endpoint)
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}

				return response.json();
			})
			.then((data) => {

				const now = data.now;
				const item = now.item;

				const progress = now.progress_ms;
				const duration = item.duration_ms;

				const percentage = (progress / duration) * 100;
				const not_played = duration - progress;
				const remaining = ((duration - (duration * (progress / duration))) / 1000);

				let header = 'what I\'m listening to right now:';
				if (!now.is_playing) {
					header += ' (paused)';
					this.$el.classList.add('paused');
				} else {
					this.$el.classList.remove('paused');
				}

				this.$el.classList.add('loaded');

				if (this.playingKey !== item.id) {
					this.playingKey = item.id;

					let artists = [];
					for (let artist in item.artists) {
						artists.push(item.artists[artist].name);
					}

					this.$el.innerHTML = ttt(this.template, {
						'imgsrc': item.album.images[1].url,
						'imgalt': item.album.name,
						'songlink': item.external_urls.spotify,
						'header': header,
						'title': item.name,
						'artist': artists.join(', '),
						'aw': percentage + '%',
						'al': (not_played / 1000) + 's',
						'state': (!now.is_playing) ? 'paused' : 'running'
					});
				}
				else {
					document.getElementById('spotify-progress').setAttribute('style', this.generateAnimationProperties(
						percentage,
						remaining,
						(!now.is_playing) ? 'paused' : 'running'
					));

					let animations = document.getAnimations();
					for (let a in animations) {
						if (animations[a].animationName === 'spotify-progress') {
							animations[a].cancel();
							animations[a].play();
						}
					}
				}
			});

	},

	generateAnimationProperties(w, l, s) {
		return ttt('--animation-width: {aw}; --animation-length: {al}; --animation-state: {state}', {
			'aw': w + '%',
			'al': l + 's',
			'state': s
		});
	}

};

export default spotify;