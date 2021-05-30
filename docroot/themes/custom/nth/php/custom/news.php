<?php

	use \Drupal\node\Entity\Node;

	class NewsQuery
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $taxonomy = array();
		protected $sort = 'date';
		protected $mode = 'nonstrict';
		protected $machine = array('article');

		const MAX_POSTS = 4;

		public function __construct($taxonomy, $sort = 'date', $mode = 'nonstrict')
		{

			$this->taxonomy = array_filter($taxonomy);
			$this->sort = $sort;
			$this->mode = $mode;

			$this->filter();

		}

		protected function filter()
		{

			$cached = '';

			$cache_tax = '';
			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					$cache_tax .= '-' . implode('-', $filter['value']);
				}
			}

			$cache_key = "news-" . date("Y-m-d-h", time()) . $cache_tax;

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('node');

				$query->condition('type', $this->machine, 'IN');

				$query->condition('status', 1);

				if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
					foreach ($this->taxonomy as $filter) {
						if (!empty($filter['value'])) {
							switch ($this->mode) {
								case 'strict':
									$query->condition($filter['field'], $filter['value']);
									break;

								default:
									$query->condition($filter['field'], $filter['value'], 'IN');
									break;
							}
						}
					}
				}

				$query->sort('field_publication_date', 'DESC');
				$query->sort('sticky');

				$posts = $query->execute();

				foreach ($posts as $key => $p) {
					$this->posts[] = $this->_load_data($p);
				}

				$this->_sort_posts();

				\Drupal::cache()->set($cache_key, $this->posts, $expire);

			}

			$this->rawPosts = $this->posts;

		}

		private function _load_data($nid)
		{

			$node = Node::load($nid);
			$return = array();

			$title = $node->title->value;
			if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
				$title = $node->field_heading->value;
			}

			$news_categories = array();
			if ($node->hasField('field_news_categories') && !$node->field_news_categories->isEmpty()) {
				$_news_categories = getTagsArray($node->get('field_news_categories'));
				if (!empty($_news_categories)) {
					$news_categories = array_merge(array(), (array) $_news_categories);
				}
			}

			$date = $node->field_publication_date->date->getTimestamp();

			$return = array(
				'nid'                   => $nid,
				'sticky'                => $node->isSticky(),
				'created'               => $date,
				'title'                 => $title,
				'field_news_categories' => $news_categories
			);

			return $return;

		}

		private function _sort_posts()
		{

			$posts_date_col = array();

			foreach ($this->posts as $j => $p) {

				$posts_date_col[] = $p['created'];

			}

			// SORT_ASC -> Oldest first
			// SORT_DESC -> Newest first
			array_multisort($posts_date_col, SORT_DESC, $this->posts);

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

					if ($post['nid'] == $item['nid']) {
						array_splice($this->posts, $j, 1);
					}

				}

			}

			return $this;

		}

		public function getYears()
		{

			$years = array();

			foreach ($this->posts as $j => $p) {

				$post_date = parse_timestamp($p['created'], 'Y');

				if (!in_array($post_date, $years)) {
					$years[] = $post_date;
				}

			}

			return $years;

		}

		public function filterByTaxonomy($field, $tid_array)
		{

			if (!empty($tid_array) && is_array($tid_array)) {
				foreach ($this->posts as $j => $p) {

					if (!empty($p[$field])) {

						if (empty(array_intersect($p[$field], $tid_array))) {
							unset($this->posts[$j]);
						}

					} else {
						unset($this->posts[$j]);
					}

				}
			}

		}

		public function filterByMonthYear($month = '', $year = '')
		{

			$month = ($month == 'all' ? '' : $month);
			$year = ($year == 'all' ? '' : $year);

			if (!empty($month) || !empty($year)) {
				foreach ($this->posts as $j => $p) {

					if (!empty($year)) {

						$post_year = parse_timestamp($p['created'], 'Y');

						if ($post_year != $year) {
							unset($this->posts[$j]);
						}

					}

					if (!empty($month)) {

						$post_month = parse_timestamp($p['created'], 'n');

						if ($post_month != $month) {
							unset($this->posts[$j]);
						}

					}

				}
			}

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

	function build_news_search($node, &$variables, &$_q)
	{

		$offset = 0;
		if (!empty($_q['page']) && is_numeric($_q['page'])) {
			$offset = $_q['page'] - 1;
		}

		$variables['offset'] = $offset;
		$variables['prev_page'] = $offset;
		$variables['current_page'] = $offset + 1;
		$variables['next_page'] = $offset + 2;

		$news_categories = getTermsFromField($node, 'field_news_categories');
		$taxonomy = array(
			getTaxonomyFinderArray('field_news_categories', $news_categories['tids'])
		);

		$q_category = '';
		if (!empty($_q['category']) && is_numeric($_q['category'])) {
			$q_category = explode(',', $_q['category']);
		}

		$q_month = '';
		if (!empty($_q['month']) && is_numeric($_q['month'])) {
			$q_month = $_q['month'];
		}

		$q_year = '';
		if (!empty($_q['year']) && is_numeric($_q['year'])) {
			$q_year = $_q['year'];
		}

		$news = new NewsQuery($taxonomy);
		$news->filterByTaxonomy('field_news_categories', $q_category);
		$news->filterByMonthYear($q_month, $q_year);

		$q_debug = nvl($_q, 'debug');

		$variables['news_query'] = $news;

		$count = 10;

		$variables['news_years'] = $news->getYears();

		$variables['news'] = load_nodes($news->results($count, $variables['offset']), 'result');

		if (!empty($q_debug)) {
			$variables['news_count'] = (int) $q_debug; //$news->count(true);
			$variables['news_count_un'] = (int) $q_debug; //$news->count();
			$variables['page_count'] = (int) $q_debug / $count; //$news->pageCount();
		} else {
			$variables['news_count'] = $news->count(true);
			$variables['news_count_un'] = $news->count();
			$variables['page_count'] = $news->pageCount($count);
		}

		$variables['pagination'] = pagination($node, $_q, $offset + 1, $variables['page_count']);

		$variables['attributes']['data-total'] = count($variables['news']); //$news->count(true); //$variables['news_count'];

	}
