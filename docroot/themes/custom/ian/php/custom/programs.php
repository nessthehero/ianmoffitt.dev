<?php

	use \Drupal\node\Entity\Node;

	class ProgramsQuery
	{

		protected $posts = array();
		protected $rawPosts = array();
		protected $events = array();
		protected $rawEvents = array();
		protected $taxonomy = array();
		protected $sort = 'date';
		protected $mode = 'nonstrict';
		protected $letters = array();

		const MAX_POSTS = 10;

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
			$this->posts = [];

			$cache_tax = '';
			if (!empty($this->taxonomy) && is_array($this->taxonomy)) {
				foreach ($this->taxonomy as $filter) {
					$cache_tax .= '-' . implode('-', $filter['value']);
				}
			}

			$cache_key = "programs-" . date("Y-m-d-h", time()) . $cache_tax;

			$cache_time = '+1 hours';
			$expire = strtotime($cache_time, time());

			if ($cached = \Drupal::cache()->get($cache_key)) {
				if (isset($cached->data) && !empty($cached->data)) {
					$this->posts = $cached->data;
				}
			}

			if (count($this->posts) == 0) {

				$query = \Drupal::entityQuery('node');

				$query->condition('type', 'program');

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

				$query->sort('title', 'ASC');

				$posts = $query->execute();

				foreach ($posts as $key => $p) {
					$this->posts[] = $this->_load_data($p);
				}

				\Drupal::cache()->set($cache_key, $this->posts, $expire);

			}

			$this->rawPosts = $this->posts;

			$this->buildLetters();

		}

		private function _load_data($nid)
		{

			$node = Node::load($nid);
			$return = array();

			$title = $node->title->value;

			if ($node->hasField('field_heading') && !$node->field_heading->isEmpty()) {
				$title = $node->field_heading->value;
			}

			$program_type = array();
			if ($node->hasField('field_program_type') && !$node->field_program_type->isEmpty()) {
				$_program_type = getTagsArray($node->get('field_program_type'));
				if (!empty($_program_type)) {
					$program_type = array_merge(array(), (array) $_program_type);
				}
			}

			$location = array();
			if ($node->hasField('field_location') && !$node->field_location->isEmpty()) {
				$_location = getTagsArray($node->get('field_location'));
				if (!empty($_location)) {
					$location = array_merge(array(), (array) $_location);
				}
			}

			$career_interest = array();
			if ($node->hasField('field_career_interest') && !$node->field_career_interest->isEmpty()) {
				$_career_interest = getTagsArray($node->get('field_career_interest'));
				if (!empty($_career_interest)) {
					$career_interest = array_merge(array(), (array) $_career_interest);
				}
			}

			$return = array(
				'nid'                   => $nid,
				'sticky'                => $node->isSticky(),
				'title'                 => $title,
				'field_program_type'    => $program_type,
				'field_location'        => $location,
				'field_career_interest' => $career_interest
			);

			return $return;

		}

		public function resetFiltering()
		{

			$this->posts = $this->rawPosts;

		}

		public function results($amt = 10, $offset = 0)
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

		private function buildLetters()
		{

			$l = array();

			foreach (range('a', 'z') as $let) {
				$l[$let] = 0;
			}

			foreach ($this->posts as $post) {

				$name = $post['title'];

				$first = strtolower($name[0]);

				$l[$first] = 1;

			}

			$this->letters = $l;

		}

		public function getLetters()
		{
			return $this->letters;
		}

		public function filterByTaxonomy($field, $tid_array)
		{

			if (!empty($tid_array) && is_array($tid_array)) {
				foreach ($this->posts as $j => $p) {

					if (!empty($p[$field])) {

						if ($field == '') {
							print_r($p['title']);
							print_r("\n");
							print_r($p[$field]);
							print_r("\n");
							print_r($tid_array);
							print_r("\n");
							print_r(array_diff($p[$field], $tid_array));
							print_r("\n");
							print_r(array_intersect($p[$field], $tid_array));
							print_r("\n");
							print_r("--------------\n");
						}

						if (empty(array_intersect($p[$field], $tid_array))) {
							unset($this->posts[$j]);

							if ($field == '') {
								print_r("Dropped\n");
								print_r("--------------\n");
							}
						}

					} else {
						unset($this->posts[$j]);
					}

				}
			}

			$this->buildLetters();

		}

		public function filterByLetter($letter)
		{

			if (!empty($letter)) {

				if ($this->letters[$letter] == 1) {

					foreach ($this->posts as $j => $p) {

						$name = $p['title'];

						$first = strtolower($name[0]);

						if ($letter != $first) {
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

	function build_program_search($node, &$variables, &$_q)
	{

		$offset = 0;
		if (!empty($_q['page']) && is_numeric($_q['page'])) {
			$offset = $_q['page'] - 1;
		}

		$variables['offset'] = $offset;
		$variables['prev_page'] = $offset;
		$variables['current_page'] = $offset + 1;
		$variables['next_page'] = $offset + 2;

		$taxonomy = array();

		// These come back as arrays
		$q_program_type = array();
		if (!empty($_q['program_type'])) {
			if (is_array($_q['program_type'])) {
				$q_program_type = $_q['program_type'];
			} else {
				$q_program_type = array($_q['program_type']);
			}
		}

		$q_location = array();
		if (!empty($_q['location'])) {
			if (is_array($_q['location'])) {
				$q_location = $_q['location'];
			} else {
				$q_location = array($_q['location']);
			}
		}

		$q_career_interest = array();
		if (!empty($_q['career_interest'])) {
			if (is_array($_q['career_interest'])) {
				$q_career_interest = $_q['career_interest'];
			} else {
				$q_career_interest = array($_q['career_interest']);
			}
		}

		$programs = new ProgramsQuery($taxonomy);
		$programs->filterByTaxonomy('field_program_type', $q_program_type);
		$programs->filterByTaxonomy('field_location', $q_location);
		$programs->filterByTaxonomy('field_career_interest', $q_career_interest);

		$variables['program_type_filters'] = getTagsFromFieldOrVocabulary($node, 'field_program_type', 'program_type', $q_program_type);
		$variables['location_filters'] = getTagsFromFieldOrVocabulary($node, 'field_location', 'location', $q_location);
		$variables['career_interest_filters'] = getTagsFromFieldOrVocabulary($node, 'field_career_interest', 'career_interest', $q_career_interest);

		$q_debug = '';
		if (!empty($_q['debug'])) {
			$q_debug = $_q['debug'];
		}

		$variables['program_query'] = $programs;

		$count = 12;

		$variables['programs'] = array();
		$program_results = $programs->results($count, $variables['offset']);
		if (!empty($program_results)) {
			foreach ($program_results as $program) {
				$variables['programs'][] = load_node($program['nid'], 'result');
			}
		}

		if (!empty($q_debug)) {
			$variables['program_count'] = (int) $q_debug; //$programs->count(true);
			$variables['program_count_un'] = (int) $q_debug; //$programs->count();
			$variables['page_count'] = (int) $q_debug / $count; //$programs->pageCount();
		} else {
			$variables['program_count'] = $programs->count(true);
			$variables['program_count_un'] = $programs->count();
			$variables['page_count'] = $programs->pageCount($count);
		}

		$variables['pagination'] = pagination($node, $_q, $offset + 1, $variables['page_count']);
		$variables['result_start'] = ($count * $offset) + 1;

		$variables['result_end'] = $variables['result_start'] + (count($program_results) - 1);

	}
