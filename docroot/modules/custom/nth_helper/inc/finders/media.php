<?php

	namespace Nth\Finders;

	use \Drupal\media\Entity\Media;

	class Images
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $machine = array();
		protected $imageStyle = '';

		const MAX_POSTS = 4;

		/**
		 * FinderQuery constructor.
		 *
		 * @param        $taxonomy
		 * @param array  $machine
		 * @param string $sort
		 * @param string $mode
		 */
		public function __construct($machine = array(), $imageStyle = '')
		{

			$this->machine = $machine;
			$this->imageStyle = $imageStyle;

			$this->filter();

		}

		protected function filter()
		{

			$cached = '';
			$this->posts = [];

			if (!empty($this->machine)) {
				if (is_array($this->machine)) {
					$cache_pre = implode('-', $this->machine);
				} else {
					$cache_pre = $this->machine;
				}
			} else {
				$cache_pre = 'media';
			}

			$cache_key = $cache_pre;

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('media');

				if (!empty($this->machine)) {
					if (is_array($this->machine)) {
						foreach ($this->machine as $machine) {
							$query->condition('bundle', $machine);
						}
					} else {
						$query->condition('bundle', $this->machine);
					}
				}

				$query->condition('status', 1);

				$posts = $query->execute();

				$node_storage = \Drupal::entityTypeManager()->getStorage('media');
				$_nodes = $node_storage->loadMultiple($posts);

				foreach ($posts as $key => $p) {
					$this->posts[] = $this->_load_data($p, $_nodes);
				}

				\Drupal::cache()->set($cache_key, $this->posts, $expire);

			}

			$this->rawPosts = $this->posts;

		}

		private function _load_data($mid, $images)
		{

			$image = $images[$mid];
			$return = array();

			if (!empty($image)) {

				$_image = \Nth\Helpers\Images::media_entity_info($image, $this->imageStyle);

				$return = array(
					'mid'    => $mid,
					'name'   => $image->name->value,
					'entity' => $image,
					'info'   => $_image,
					'url'    => $_image['url'],
					'alt'    => $_image['alt']
				);

			}

			return $return;

		}

		public function resetFiltering()
		{

			$this->posts = $this->rawPosts;

		}

		public function results($amt = MAX_POSTS, $offset = 0)
		{

			if ($amt == -1) {
				return $this->posts;
			} else {
				$slice = array_slice($this->posts, ($offset * $amt), $amt);

				return $slice;
			}

		}

		public function remove($items)
		{

			foreach ($items as $key => $item) {

				foreach ($this->posts as $j => $post) {

					if ($post['mid'] == $item) {
						array_splice($this->posts, $j, 1);
					}

				}

			}

			return $this;

		}

		public function shuffle()
		{

			shuffle($this->posts);

		}

		public function count($filtered = false)
		{

			if ($filtered) {
				return count($this->posts);
			} else {
				return count($this->rawPosts);
			}

		}

		public function pageCount($amt = 10)
		{

			return ceil(count($this->posts) / $amt);

		}

	}

