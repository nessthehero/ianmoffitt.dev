<?php

	namespace Nth\Finders;

//	use Nth\Helpers\Nodes;
//	use Nth\Helpers\Taxonomy;
//  use Nth\Helpers\Date;
//  use Nth\Utils\Markup;
//	use \Drupal\node\Entity\Node;
//
//	class News
//	{
//
//		protected $posts = array();
//		protected $rawPosts = array();
//		protected $taxonomy = array();
//		protected $sort = 'date';
//		protected $mode = 'nonstrict';
//		protected $machine = array('article');
//
//		const MAX_POSTS = 4;
//
//		public function __construct($taxonomy, $sort = 'date', $mode = 'nonstrict')
//		{
//
//			$this->taxonomy = array_filter($taxonomy);
//			$this->sort = $sort;
//			$this->mode = $mode;
//
//			$this->filter();
//
//		}
//
//		protected function filter()
//		{
//
//			$cached = '';
//
//			$cache_tax = '';
//			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
//				foreach ($this->taxonomy as $filter) {
//					$cache_tax .= '-' . implode('-', $filter['value']);
//				}
//			}
//
//			$cache_key = "news-" . date("Y-m-d-h", time()) . $cache_tax;
//
//			$cache_time = '+1 hours';
//			$expire = strtotime($cache_time, time());
//
//			if ($cached = \Drupal::cache()->get($cache_key)) {
//				if (isset($cached->data) && !empty($cached->data)) {
//					$this->posts = $cached->data;
//				}
//			}
//
//			if (count($this->posts) == 0) {
//
//				$query = \Drupal::entityQuery('node');
//
//				$query->condition('type', 'article');
//
//				$query->condition('status', 1);
//
//				if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
//					foreach ($this->taxonomy as $filter) {
//						if (!empty($filter['value'])) {
//							switch ($this->mode) {
//								case 'strict':
//									$query->condition($filter['field'], $filter['value']);
//									break;
//
//								default:
//									$query->condition($filter['field'], $filter['value'], 'IN');
//									break;
//							}
//						}
//					}
//				}
//
//				$query->sort('field_publication_date', 'DESC');
//				$query->sort('sticky');
//
//				$posts = $query->execute();
//
//				foreach ($posts as $key => $p) {
//					$this->posts[] = $this->_load_data($p);
//				}
//
//				$this->_sort_posts();
//
//				\Drupal::cache()->set($cache_key, $this->posts, $expire);
//
//			}
//
//			$this->rawPosts = $this->posts;
//
//		}
//
//		private function _load_data($nid)
//		{
//
//			$node = Node::load($nid);
//			$return = array();
//
//			$title = $node->getTitle();
//			if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
//				$title = $node->field_heading->value;
//			}
//
//			$teaser = '';
//			if ($node->hasField('field_teaser') && !$node->field_teaser->isEmpty()) {
//				$teaser = $node->get('field_teaser')->value;
//			}
//
//			$department = array();
//			if ($node->hasField('field_department') && !$node->field_department->isEmpty()) {
//				$_department = Taxonomy::getTagsArray($node->get('field_department'));
//				if (!empty($_department)) {
//					$department = array_merge(array(), (array) $_department);
//				}
//			}
//
//			$news_type = array();
//			if ($node->hasField('field_news_type') && !$node->field_news_type->isEmpty()) {
//				$_news_type = Taxonomy::getTagsArray($node->get('field_news_type'));
//				if (!empty($_news_type)) {
//					$news_type = array_merge(array(), (array) $_news_type);
//				}
//			}
//
//			$search_words = strtolower($title . ' ' . $teaser);
//
//			$date = $node->field_publication_date->date->getTimestamp();
//
//			$return = array(
//				'nid'             => $nid,
//				'sticky'          => $node->isSticky(),
//				'created'         => $date,
//				'title'           => $title,
//				'teaser'           => $teaser,
//				'search_words'     => $search_words,
//				'field_news_type' => $news_type,
//				'field_department' => $department
//			);
//
//			return $return;
//
//		}
//
//		private function _sort_posts()
//		{
//
//			$posts_date_col = array();
//
//			foreach ($this->posts as $j => $p) {
//
//				$posts_date_col[] = $p['created'];
//
//			}
//
//			// SORT_ASC -> Oldest first
//			// SORT_DESC -> Newest first
//			array_multisort($posts_date_col, SORT_DESC, $this->posts);
//
//		}
//
//		public function resetFiltering()
//		{
//
//			$this->posts = $this->rawPosts;
//
//		}
//
//		public function results($amt = MAX_POSTS, $offset = 0)
//		{
//
//			if ($amt == -1) {
//				return $this->posts;
//			} else {
//				$slice = array_slice($this->posts, ($offset * $amt), $amt);
//
//				return $slice;
//			}
//
//		}
//
//		public function remove($items)
//		{
//
//			foreach ($items as $key => $item) {
//
//				foreach ($this->posts as $j => $post) {
//
//					if ($post['nid'] == $item['nid']) {
//						array_splice($this->posts, $j, 1);
//					}
//
//				}
//
//			}
//
//			return $this;
//
//		}
//
//		public function getYears()
//		{
//
//			$years = array();
//
//			foreach ($this->posts as $j => $p) {
//
//				$post_date = Date::parse_timestamp($p['created'], 'Y');
//
//				if (!in_array($post_date, $years)) {
//					$years[] = $post_date;
//				}
//
//			}
//
//			return $years;
//
//		}
//
//		public function filterByTaxonomy($field, $tid_array)
//		{
//
//			if (!empty($tid_array) && is_array($tid_array)) {
//				foreach ($this->posts as $j => $p) {
//
//					if (!empty($p[$field])) {
//
//						if (empty(array_intersect($p[$field], $tid_array))) {
//							unset($this->posts[$j]);
//						}
//
//					} else {
//						unset($this->posts[$j]);
//					}
//
//				}
//			}
//
//		}
//
//		public function filterByKeywords($keywords)
//		{
//
//			if (!empty($keywords)) {
//
//				foreach ($this->posts as $j => $p) {
//
//					$text_storage = $p['search_words'];
//
//					if (strpos($text_storage, $keywords) === false) {
//
//						unset($this->posts[$j]);
//
//					}
//
//				}
//
//			}
//
//		}
//
//		public function filterByMonthYear($month = '', $year = '')
//		{
//
//			$month = ($month == 'all' ? '' : $month);
//			$year = ($year == 'all' ? '' : $year);
//
//			if (!empty($month) || !empty($year)) {
//				foreach ($this->posts as $j => $p) {
//
//					if (!empty($year)) {
//
//						$post_year = Date::parse_timestamp($p['created'], 'Y');
//
//						if ($post_year != $year) {
//							unset($this->posts[$j]);
//						}
//
//					}
//
//					if (!empty($month)) {
//
//						$post_month = Date::parse_timestamp($p['created'], 'n');
//
//						if ($post_month != $month) {
//							unset($this->posts[$j]);
//						}
//
//					}
//
//				}
//			}
//
//		}
//
//		public function count($filtered = false)
//		{
//
//			if ($filtered) {
//				return count($this->posts);
//			} else {
//				return count($this->rawPosts);
//			}
//
//		}
//
//		public function pageCount($amt = 10)
//		{
//
//			return ceil(count($this->posts) / $amt);
//
//		}
//
//	}
//
//	function build_news_filters($node, &$variables, &$_q)
//	{
//
//		$formIndex = 1;
//
//		$q_type = array();
//		if (!empty($_q['type']) && is_array($_q['type'])) {
//			$q_type = explode(',', $_q['type'][0]);
//		}
//
//		$variables['filter_news_type'] = array();
//		if ($node->hasField('field_filter_news_type')) {
//			$variables['filter_news_type'] = Taxonomy::getTermsFromField($node, 'field_filter_news_type', $q_type);
//		}
//
//		$q_department = array();
//		if (!empty($_q['department']) && is_array($_q['department'])) {
//			$q_department = explode(',', $_q['department'][0]);
//		}
//
//		$variables['filters_department'] = array();
//		if ($node->hasField('field_filter_departments')) {
//			$variables['filters_department'] = Taxonomy::getTermsFromField($node, 'field_filter_departments', $q_department);
//		}
//
//		$q_year = '';
//		if (!empty($_q['year']) && is_string($_q['year'])) {
//			$q_year = $_q['year'];
//		}
//
//		$variables['filter_year'] = $q_year;
//
//		$q_month = '';
//		if (!empty($_q['month']) && is_string($_q['month'])) {
//			$q_month = $_q['month'];
//		}
//
//		$variables['filter_month'] = $q_month;
//
//		$q_search = '';
//		$variables['search_query'] = '';
//		if (!empty($_q['search']) && is_string($_q['search'])) {
//			$variables['search_query'] = $_q['search'];
//			$q_search = strtolower($_q['search']);
//		}
//
//		$variables['default_keywords'] = $q_search;
//
//		// Chips
//		$active_type = Taxonomy::getActiveTaxonomy($variables['filter_news_type']);
//		$active_department = Taxonomy::getActiveTaxonomy($variables['filters_department']);
//
//		$chips = array();
//
//		if (!empty($q_search)) {
//			$chips[] = array(
//				'label' => $q_search,
//				'safe'  => 'search-' . $formIndex
//			);
//		}
//
//		if (!empty($active_type)) {
//			foreach ($active_type as $item) {
//				$chips[] = array(
//					'label' => $item['label'],
//					'safe'  => 'type-' . $formIndex . '-' . $item['tid']
//				);
//			}
//		}
//
//		if (!empty($active_department)) {
//			foreach ($active_department as $item) {
//				$chips[] = array(
//					'label' => $item['label'],
//					'safe'  => 'department-' . $formIndex . '-' . $item['tid']
//				);
//			}
//		}
//
//		if (!empty($q_year)) {
//			$chips[] = array(
//				'label' => $q_year,
//				'safe'  => 'filter-year'
//			);
//		}
//
//		if (!empty($q_month)) {
//			$chips[] = array(
//				'label' => date('M', mktime(0, 0, 0, $q_month, 1)),
//				'safe'  => 'filter-month'
//			);
//		}
//
//		$variables['chips'] = $chips;
//
//	}
//
//	function build_news_search($node, &$variables, &$_q)
//	{
//
//		\Nth\Finders\build_news_filters($node, $variables, $_q);
//
//		$offset = 0;
//		if (!empty($_q['page']) && is_numeric($_q['page'])) {
//			$offset = $_q['page'] - 1;
//		}
//
//		$variables['offset'] = $offset;
//		$variables['prev_page'] = $offset;
//		$variables['current_page'] = $offset + 1;
//		$variables['next_page'] = $offset + 2;
//
//		$type = Taxonomy::getTermsFromField($node, 'field_news_type');
//		$department = Taxonomy::getTermsFromField($node, 'field_department');
//		$taxonomy = array(
//			Taxonomy::getTaxonomyFinderArray('field_department', $department['tids']),
//			Taxonomy::getTaxonomyFinderArray('field_news_type', $type['tids'])
//		);
//
//		$q_type = array();
//		if (!empty($_q['type']) && is_array($_q['type'])) {
//			$q_type = explode(',', $_q['type'][0]);
//		}
//
//		$q_department = array();
//		if (!empty($_q['department']) && is_array($_q['department'])) {
//			$q_department = explode(',', $_q['department'][0]);
//		}
//
//		$q_year = '';
//		if (!empty($_q['year']) && is_string($_q['year'])) {
//			$q_year = intval($_q['year']);
//		}
//
//		$q_month = '';
//		if (!empty($_q['month']) && is_string($_q['month'])) {
//			$q_month = $_q['month'];
//		}
//
//		$q_search = '';
//		$variables['search_query'] = '';
//		if (!empty($_q['search']) && is_string($_q['search'])) {
//			$variables['search_query'] = $_q['search'];
//			$q_search = strtolower($_q['search']);
//		}
//
//		$news = new News($taxonomy);
//
//		$variables['years_options'] = $news->getYears();
//
//		$news->filterByTaxonomy('field_news_type', $q_type);
//		$news->filterByTaxonomy('field_department', $q_department);
//		$news->filterByKeywords($q_search);
//		$news->filterByMonthYear($q_month, $q_year);
//
//		$variables['news_query'] = $news;
//
//		$count = 12;
//
//		$variables['news'] = Nodes::load_nodes($news->results($count, $variables['offset']), 'card');
//
//		$totalItems = $news->count(true);
//		$totalPages = $news->pageCount($count);
//
//		$variables['count'] = $totalItems;
//		$variables['page_count'] = $totalPages;
//		$variables['start'] = ($offset * $count) + 1;
//
//		if ($totalItems <= $count) {
//			$variables['end'] = $totalItems;
//		} else {
//			$variables['end'] = $variables['start'] + ($count - 1);
//
//			if ($variables['end'] >= $totalItems) {
//				$variables['end'] = $totalItems;
//			}
//		}
//
//		$variables['pagination'] = Markup::pagination($node, $_q, $offset + 1, $variables['page_count']);
//
//	}
