/**
 * Expand component.
 * @module components/grid
 */

const expand = {

	trigger: '.expand .content__header h1, .expand .content__header, .expand .content__trigger',

	init() {

		this.bindActions();

	},

	bindActions() {

		document.addEventListener('click', (e) => {

			console.log(e);
			
			if (e.target.matches(this.trigger)) {
				this.toggle(e.target);
				console.log('hi!');
			}
			
		});

	},

	toggle($el) {

		const $parent = $el.closest('.expand');

		console.log($parent);

		if ($parent) {

			const $content = $parent.querySelector('.expand__content');
			const $copy = $content.querySelector('.user-markup');

			if ($parent.matches('.expand--open')) {
				$content.style.maxHeight = null;
				console.log(1);
				$parent.classList.remove('expand--open');
				$parent.classList.add('expand--closed');
			} else if ($parent.matches('.expand--closed')) {
				console.log(2);
				$content.style.maxHeight = ($el.scrollHeight + $copy.scrollHeight) + 'px';
				$parent.classList.remove('expand--closed');
				$parent.classList.add('expand--open');
			}

		}

		// if ($el.matches('.'))

	},

};

export default expand;