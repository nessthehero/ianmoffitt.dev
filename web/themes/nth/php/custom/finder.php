<?php

	use \Drupal\node\Entity\Node;

	class FinderQuery
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $taxonomy = array();
		protected $sort = 'date';
		protected $mode = 'nonstrict';
		protected $machine = array();
		protected $promoted = false;

		const MAX_POSTS = 4;
		const PAGE_SIZE = 10;

		public function __construct($taxonomy, $machine = array('article'), $sort = 'date', $mode = 'nonstrict')
		{

			$this->taxonomy = array_filter($taxonomy);
			$this->sort = $sort;
			$this->mode = $mode;
			$this->machine = $machine;

			$this->filter();

		}

		protected function filter()
		{

			$cached = '';
			$this->posts = [];

			$cache_tax = '';
			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					$cache_tax .= '-' . implode('-', $filter['value']);
				}
			}

			if (!empty($this->machine)) {
				$cache_pre = implode('-', $this->machine);
			} else {
				$cache_pre = 'node';
			}

			$cache_key = $cache_pre . '-' . (string) $this->promoted . '-' . date('Y-m-d-h', time()) . $cache_tax;

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('node');

				if (!empty($this->machine)) {
					$query->condition('type', $this->machine, 'IN');
				}

				$query->condition('status', 1);

				if ($this->promoted) {
					$query->condition('promote', 1, '=');
				}

				if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
					foreach ($this->taxonomy as $filter) {
						if (count($filter['value']) > 0) {
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

				$query->sort('created', 'DESC');

				$posts = $query->execute();

				foreach ($posts as $key => $p) {
					$this->posts[] = $this->_load_data($p);
				}

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

			$return = array(
				'nid'             => $nid,
				'sticky'          => $node->isSticky(),
				'promoted'        => $node->isPromoted(),
				'title'           => $title
			);

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

					if ($post['nid'] == $item['nid']) {
						array_splice($this->posts, $j, 1);
					}

				}

			}

			return $this;

		}

		public function getOnlyPromoted()
		{

			$this->promoted = true;

			$this->filter();

		}

		public function filterByKeyword($keyword = '')
		{

//			$filteredposts = array();
//
//			if (!empty($keyword)) {
//
//				foreach ($this->posts as $j => $p) {
//
//					$title = $p->title;
//					$body = nv($p, 'body');
//					$summ = nv($p, 'body', 'teaser');
//
//					$haystack = strtolower($title . ' ' . render($body) . ' ' . render($summ));
//					$needle = strtolower($key);
//
//					if (strpos($haystack, $needle) !== false) {
//						$filteredposts[] = $p;
//					}
//
//				}
//
//				$this->posts = $filteredposts;
//
//			}

		}

		public function filterByTaxonomy($field, $tid_array)
		{

//			if (!empty($tid_array) && is_array($tid_array)) {
//				foreach ($this->posts as $j => $p) {
//					$tags = nv($p, $field);
//					$check_tax = array();
//
//					if (!empty($tags['tid'])) {
//						$check_tax[] = $tags['tid'];
//					} else {
//						foreach ($tags as $key => $value) {
//							if (!empty($value['tid'])) {
//								$check_tax[] = $value['tid'];
//							}
//						}
//					}
//
//					foreach ($tid_array as $key => $value) {
//						if (!in_array($value, $check_tax)) {
//							unset($this->posts[$j]);
//						}
//					}
//				}
//			}

		}

		public function sortByDate()
		{

//			$this->sort = 'date';
//
//			$this->filter();

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

	function build_finder_search($node, &$variables, &$_q)
	{

		$offset = 0;
		if (!empty($_q['page']) && is_numeric($_q['page'])) {
			$offset = $_q['page'];
		}

		$variables['offset'] = $offset;
		$variables['prev_page'] = $offset;
		$variables['current_page'] = $offset + 1;
		$variables['next_page'] = $offset + 2;

//		$t_tags = getTagsArray($node, 'field_general_tags');
//		$t_faculty = getTagsArray($node, 'field_faculty_tags');
//		$t_programs = getTagsArray($node, 'field_program_tags');
//		$t_labs = getTagsArray($node, 'field_lab_tags');
//		$t_centers = getTagsArray($node, 'field_center_tags');
//		$t_projects = getTagsArray($node, 'field_project_tags');

		$taxonomy = array();
//		$taxonomy = array(
//			array(
//				'field' => 'field_general_tags',
//				'value' => $t_tags
//			),
//			array(
//				'field' => 'field_faculty_tags',
//				'value' => $t_faculty
//			),
//			array(
//				'field' => 'field_program_tags',
//				'value' => $t_programs
//			),
//			array(
//				'field' => 'field_lab_tags',
//				'value' => $t_labs
//			),
//			array(
//				'field' => 'field_center_tags',
//				'value' => $t_centers
//			),
//			array(
//				'field' => 'field_project_tags',
//				'value' => $t_projects
//			)
//		);

		$variables['searching'] = false;
		$variables['search_query'] = '';
		$q_builder = array();

//		$q_tag = '';
//		if (!empty($_q['tag']) && is_numeric($_q['tag'])) {
//			$q_tag = explode(',', $_q['tag']);
//			$variables['searching'] = true;
//		}
//
//		$q_faculty = '';
//		if (!empty($_q['faculty']) && is_numeric($_q['faculty'])) {
//			$q_faculty = explode(',', $_q['faculty']);
//			$variables['searching'] = true;
//		}
//
//		$q_program = '';
//		if (!empty($_q['program']) && is_numeric($_q['program'])) {
//			$q_program = explode(',', $_q['program']);
//			$variables['searching'] = true;
//		}
//
//		$q_lab = '';
//		if (!empty($_q['lab']) && is_numeric($_q['lab'])) {
//			$q_lab = explode(',', $_q['lab']);
//			$variables['searching'] = true;
//		}
//
//		$q_center = '';
//		if (!empty($_q['center']) && is_numeric($_q['center'])) {
//			$q_center = explode(',', $_q['center']);
//			$variables['searching'] = true;
//		}
//
//		$q_project = '';
//		if (!empty($_q['project']) && is_numeric($_q['project'])) {
//			$q_project = explode(',', $_q['project']);
//			$variables['searching'] = true;
//		}

		$finder = new FinderQuery($taxonomy);
//		$project->filterByTaxonomy('field_general_tags', $q_tag);
//		$project->filterByTaxonomy('field_faculty_tags', $q_faculty);
//		$project->filterByTaxonomy('field_program_tags', $q_program);
//		$project->filterByTaxonomy('field_lab_tags', $q_lab);
//		$project->filterByTaxonomy('field_center_tags', $q_center);
//		$project->filterByTaxonomy('field_project_tags', $q_project);

		$q_debug = nvl($_q, 'debug');

		$variables['finder_query'] = $finder;

		$count = 10;
		if (!empty($variables['searching'])) {
			$count = $finder->count();
			$variables['offset'] = 0;
		}

		$variables['founditems'] = $finder->results($count, $variables['offset']);
		if (!empty($q_debug)) {
			$variables['finder_count'] = (int) $q_debug; //$finder->count(true);
			$variables['finder_count_un'] = (int) $q_debug; //$finder->count();
			$variables['page_count'] = (int) $q_debug / 10; //$finder->pageCount();
		} else {
			$variables['finder_count'] = $finder->count(true);
			$variables['finder_count_un'] = $finder->count();
			$variables['page_count'] = $finder->pageCount();
		}

	}
